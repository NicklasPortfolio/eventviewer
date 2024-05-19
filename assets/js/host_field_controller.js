$(document).ready(function () {
  const selectedHostsDiv = $("#selectedHosts");
  const selectedHosts = new Set();
  const selectedHostIdsInput = $("#selectedHostIds");

  function updateHostIdsInput() {
    selectedHostIdsInput.val(Array.from(selectedHosts).join(","));
  }

  $(".host-option").on("click", function (event) {
    event.preventDefault();
    const hostId = $(this).data("host-id");
    const hostName = $(this).text();

    if (!selectedHosts.has(hostId)) {
      selectedHosts.add(hostId);
      const hostBadge = $(
        '<span class="badge badge-primary m-1 host-badge"></span>'
      ).text(hostName);
      const removeIcon = $(
        '<span class="ml-2" style="cursor: pointer;">&times;</span>'
      );

      removeIcon.on("click", function () {
        selectedHosts.delete(hostId);
        hostBadge.remove();
        updateHostIdsInput();
      });

      hostBadge.append(removeIcon);
      selectedHostsDiv.append(hostBadge);
      updateHostIdsInput();
    }
  });
});
