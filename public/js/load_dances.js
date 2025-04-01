function escapeHtml(text) {
    if (typeof text !== 'string') return text;
    return text
        .replace(/&/g, "&amp;")
        .replace(/</g, "&lt;")
        .replace(/>/g, "&gt;")
        .replace(/"/g, "&quot;")
        .replace(/'/g, "&#039;");
}

function createDanceCard(dance) {
    const imageHtml = dance.media_url
        ? `<img src="${escapeHtml(dance.media_url)}" alt="${escapeHtml(dance.alttext)}">`
        : "<p>No Image Available</p>";

    const descriptionHtml = dance.description
        ? `<p class="danceDescription">${escapeHtml(dance.description)}</p>`
        : "<p class='danceDescription'>No description available</p>";

    const regionHtml = dance.region
        ? `<p class="danceRegion"><strong>Region:</strong> ${escapeHtml(dance.region)}</p>`
        : "<p class='danceRegion'><strong>Region:</strong> Unknown</p>";

    const categoryHtml = dance.category
        ? `<p class="danceCategory"><strong>Category:</strong> ${escapeHtml(dance.category)}</p>`
        : "<p class='danceCategory'><strong>Category:</strong> Uncategorized</p>";

    return `
    <div class="dance" data-dance-id="${dance.dance_id}">
      <h3 class="danceName">${escapeHtml(dance.dance_name)}</h3>
      ${imageHtml}
      ${descriptionHtml}
      ${regionHtml}
      ${categoryHtml}
    </div>
  `;
}

function loadDances(region = null, category = null) {
    const danceContainer = document.getElementById("danceContainer");
    danceContainer.innerHTML = "<p>Loading dances...</p>"; // Show loading message

    const requestBody = JSON.stringify({ region, category });

    fetch("../src/api/fetch_dances.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: requestBody
    })
        .then(response => response.json())
        .then(data => {
            if (danceContainer.classList.contains("scrolling")) {
                // Create a wrapper for the dance cards if scrolling is enabled
                danceContainer.innerHTML = "<div class='dance-wrapper'></div>";
                const wrapper = danceContainer.querySelector(".dance-wrapper");

                if (data.length) {
                    data.forEach(dance => {
                        // Only show the dance name and image for scrolling cards
                        const danceCard = `
              <div class="dance" data-dance-id="${dance.dance_id}">
                <h3 class="danceName">${escapeHtml(dance.dance_name)}</h3>
                ${
                            dance.media_url
                                ? `<img src="${escapeHtml(dance.media_url)}" alt="${escapeHtml(dance.alttext)}">`
                                : "<p>No Image Available</p>"
                        }
              </div>
            `;
                        wrapper.insertAdjacentHTML("beforeend", danceCard);
                    });
                } else {
                    wrapper.innerHTML = "<p>No dances found.</p>";
                }
            } else {
                // For non-scrolling pages, you can use the full dance card as needed
                danceContainer.innerHTML = "";
                if (data.length) {
                    data.forEach(dance => {
                        const danceCard = `
              <div class="dance" data-dance-id="${dance.dance_id}">
                <h3 class="danceName">${escapeHtml(dance.dance_name)}</h3>
                ${
                            dance.media_url
                                ? `<img src="${escapeHtml(dance.media_url)}" alt="${escapeHtml(dance.alttext)}">`
                                : "<p>No Image Available</p>"
                        }
                ${dance.description ? `<p class="danceDescription">${escapeHtml(dance.description)}</p>` : "<p class='danceDescription'>No description available</p>"}
                ${dance.region ? `<p class="danceRegion"><strong>Region:</strong> ${escapeHtml(dance.region)}</p>` : "<p class='danceRegion'><strong>Region:</strong> Unknown</p>"}
                ${dance.category ? `<p class="danceCategory"><strong>Category:</strong> ${escapeHtml(dance.category)}</p>` : "<p class='danceCategory'><strong>Category:</strong> Uncategorized</p>"}
              </div>
            `;
                        danceContainer.insertAdjacentHTML("beforeend", danceCard);
                    });
                } else {
                    danceContainer.innerHTML = "<p>No dances found.</p>";
                }
            }
        })
        .catch(error => {
            console.error("Error fetching dances:", error);
            danceContainer.innerHTML = "<p>Error loading data.</p>";
        });
}
        //This function loads a single dance based on the dance ID to display details for single dance
        function loadSingleDance(danceId) {

            const danceContainer = document.getElementById("danceContainer");
            danceContainer.innerHTML = "<p>Loading dance...</p>";
            
            //This calls the backend api to fetch the dance details from the db
            fetch("../src/api/fetch_dances.php", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ id: danceId })
            })
            .then(response => response.json())
            .then(data => {
            
            danceContainer.innerHTML = "";
        
           // Check if data is an array and has at least one element and converts data into page, else shows no dance found
            if (Array.isArray(data) && data.length > 0) {
                const dance = data[0];
                const pageHtml = createDancePage(dance);
                danceContainer.insertAdjacentHTML("beforeend", pageHtml);
            } else {
                danceContainer.innerHTML = "<p>No dance found.</p>";
            }
            })
            .catch(error => {
            console.error("Error fetching dance:", error);
            danceContainer.innerHTML = "<p>Error loading data.</p>";
            });
        }

        //This function creates the dance page with all the details of the dance 
        function createDancePage(dance) {
            return `
            <div class="dance-page" style="
              max-width: 600px;
              margin: 2rem auto;
              text-align: center;
              border: 1px solid #ddd;
              padding: 1rem;
              border-radius: 8px;
            ">
              <h2 style="margin-bottom: 1rem;">${escapeHtml(dance.dance_name)}</h2>
              ${
                dance.media_url 
                  ? `<img 
                       src="${escapeHtml(dance.media_url)}"
                       alt="${escapeHtml(dance.alttext)}"
                       style="
                         max-width: 400px;
                         height: auto;
                         display: block;
                         margin: 0 auto 1rem; 
                       "
                     />`
                  : ""
              }
            
              <div class="dance-details" style="text-align: left; margin: 0 auto; max-width: 400px;">
                <p><strong>Region:</strong> ${escapeHtml(dance.region)}</p>
                <p><strong>Category:</strong> ${escapeHtml(dance.category)}</p>
                <p class="danceDescription">${escapeHtml(dance.description)}</p>
        
                
                ${isAdmin ? `
<div style="text-align: center; margin-top: 1rem;">
  <button class="dance-update-btn" data-dance-id="${dance.dance_id}">Update</button>
  <button class="dance-delete-btn" data-dance-id="${dance.dance_id}" style="margin-left: 1rem;">Delete</button>
</div>
` : ""}

              </div>
            </div>
          `;
          }