  const dialog = document.getElementById("backlogDialog");
  const form = document.getElementById("backlogForm");

  // Attach click listeners to all “Add to Backlog” buttons
  document.querySelectorAll(".open-backlog-modal").forEach(button => {
    button.addEventListener("click", () => {
      // Fill the modal's hidden fields
      document.getElementById("modal-type").value = button.dataset.type;
      document.getElementById("modal-name").value = button.dataset.name;
      document.getElementById("modal-spotify-id").value = button.dataset.spotifyId;
      document.getElementById("modal-image").value = button.dataset.image;
      document.getElementById("modal-release-date").value = button.dataset.releaseDate || "";
      document.getElementById("modal-artists").value = button.dataset.artists || "";

      // Open modal
      dialog.showModal();
    });
  });