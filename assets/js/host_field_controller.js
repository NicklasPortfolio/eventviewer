$(document).ready(function () {
  // Hämta div för att visa valda hosts
  const selectedHostsDiv = $("#selectedHosts");

  // Set för att hålla valda hosts
  const selectedHosts = new Set();

  // Input för att spara valda host IDs
  const selectedHostIdsInput = $("#selectedHostIds");

  // Uppdatera input med valda host IDs
  function updateHostIdsInput() {
    selectedHostIdsInput.val(Array.from(selectedHosts).join(","));
  }

  // Klick-event för host options
  $(".host-option").on("click", function (event) {
    event.preventDefault(); // Förhindra standard klick-grej
    const hostId = $(this).data("host-id"); // Hämta host ID från data-attribut
    const hostName = $(this).text(); // Hämta host name från texten

    // Kolla om host redan är vald
    if (!selectedHosts.has(hostId)) {
      selectedHosts.add(hostId); // Lägg till host ID i set

      // Skapa badge för vald host
      const hostBadge = $(
        '<span class="badge badge-primary m-1 host-badge"></span>'
      ).text(hostName);

      // Skapa "remove" icon
      const removeIcon = $(
        '<span class="ml-2" style="cursor: pointer;">&times;</span>'
      );

      // Klick-event för att ta bort host
      removeIcon.on("click", function () {
        selectedHosts.delete(hostId); // Ta bort host ID från set
        hostBadge.remove(); // Ta bort badge från DOM
        updateHostIdsInput(); // Uppdatera input med host IDs
      });

      // Lägg till remove-icon till badge
      hostBadge.append(removeIcon);

      // Lägg till badge till div
      selectedHostsDiv.append(hostBadge);

      // Uppdatera input med valda host IDs
      updateHostIdsInput();
    }
  });
});
