import Groq from "groq-sdk";
import express from "express";
import cors from "cors";
import dotenv from "dotenv";
import mysql from "mysql2/promise";
import fs from "fs";

dotenv.config({ path: "config/.env" });

const app = express();
import path from "path";
import { fileURLToPath } from "url";

// Needed for ES modules
const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

// Serve static files from the "public" directory
app.use(express.static(path.join(__dirname, "/../public")));

app.use(express.json());
app.use(cors({
  origin: "*",
  methods: ["GET", "POST"],
  allowedHeaders: ["Content-Type"]
}));


const logFile = path.resolve("chatbot.log");
const log = (message) => {
  const timestamp = new Date().toISOString();
  const entry = `[${timestamp}] ${message}\n`;
  fs.appendFileSync(logFile, entry);
  console.log(entry); // Also show logs in terminal
};

const groq = new Groq({ apiKey: process.env.GROQ_API_KEY });

// ✅ Setup DB connection
const db = await mysql.createConnection({
  host: "127.0.0.1",
  port: 3306,
  user: "root",
  password: "",
  database: "brazil_dances"
});

const systemMessage = {
  role: "system",
  content: `
You are an assistant that can answer natural questions about Brazilian dances and query a MySQL database when needed.

If the question asks about a dance, you should search the database for the dance first. If the dance is not found,
you should answer about it in your own words. Prioritize searching for the dance in the database.

Only use SELECT queries. No DELETE/UPDATE/INSERT.

Database schema:
---
Table: dance_categories
- category_id, category_name

Table: dances
- dance_id, dance_name, category_id, description, media_id, region, user_id, approved, x, y

Table: region
- region_key, region_name
---
Respond with only the SQL query if a query is needed. Otherwise, answer naturally.
Only use SELECT queries. Never use INSERT, UPDATE, DELETE, DROP, ALTER, CREATE, or any statement that changes data.

`
};

app.post("/chat", async (req, res) => {
  const userMessage = req.body.message;

  if (!userMessage) {
    log("Received empty message.");
    return res.status(400).json({ error: "Message is required" });
  }

  log(`User: ${userMessage}`);

  try {
    // Step 1: Get AI response
    const chatCompletion = await groq.chat.completions.create({
      messages: [systemMessage, { role: "user", content: userMessage }],
      model: "llama-3.3-70b-versatile",
    });

    const aiOutput = chatCompletion.choices[0]?.message?.content?.trim();
    log(`AI Initial Output:\n${aiOutput}`);

    // Step 2: Check if it's a SQL query or natural response
    const isSQL = aiOutput?.toLowerCase().startsWith("select");

    const forbiddenPatterns = [
      /\b(insert|update|delete|drop|alter|truncate|create|replace|grant|revoke)\b/i,
      /--/,
      /;/
    ];

    const isMalicious = forbiddenPatterns.some((pattern) => pattern.test(aiOutput));

    if (isMalicious) {
      log(`❌ Potentially unsafe SQL detected:\n${aiOutput}`);
      return res.status(400).json({ error: "Unsafe query rejected." });
    }

    if (!isSQL) {
      log(`✅ AI Natural Response:\n${aiOutput}`);
      return res.json({ response: aiOutput });
    }



    // Step 3: Execute the SQL query
    log(`Executing SQL: ${aiOutput}`);
    const [rows] = await db.execute(aiOutput);
    log(`SQL Result: ${JSON.stringify(rows)}`);

    // Step 4: Summarize the result
    const summaryPrompt = [
      {
        role: "system",
        content: "You are a helpful assistant. Summarize this database result for the user's original question in one clear, short sentence."
      },
      {
        role: "user",
        content: `Question: ${userMessage}\n\nResult: ${JSON.stringify(rows)}`
      }
    ];

    const summary = await groq.chat.completions.create({
      messages: summaryPrompt,
      model: "llama-3.3-70b-versatile",
    });

    const finalAnswer = summary.choices[0]?.message?.content?.trim() || "No answer.";
    log(`AI Summary Response: ${finalAnswer}`);

    res.json({ response: finalAnswer });

  } catch (error) {
    console.error("Error:", error);
    log(`❌ Error: ${error.message}`);
    res.status(500).json({ error: "Internal server error" });
  }
});

const PORT = 3000;
app.listen(PORT, '0.0.0.0', () => {
  log(`✅ Chatbot server running on http://0.0.0.0:${PORT}`);
});

