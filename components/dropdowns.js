/**
 * Notification Dropdown Tab Switching
 */
function changeDropdownItem(element) {
  const tabName = element.getAttribute('data-tab');

  // Remove active class from all tab buttons
  const tabButtons = element.closest('.notification-tabs').querySelectorAll('.tab-btn');
  tabButtons.forEach(btn => btn.classList.remove('active'));

  // Add active class to clicked button
  element.classList.add('active');

  // Hide all tab contents
  const tabContents = document.querySelectorAll('.notification-tab-content');
  tabContents.forEach(content => content.classList.remove('active'));

  // Show selected tab content
  const targetContent = document.getElementById(`notification-tab-${tabName}`);
  if (targetContent) {
    targetContent.classList.add('active');
  }
}

/**
 * Delete all notifications
 */
const onClickNotiReadAll = function() {
  function confirmHandler() {
    exec_json('ncenterlite.procNcenterliteNotifyReadAll', null, function(res) {
      console.log(res);
      window.location.reload();
    },
    function(err) {
      console.log(err);
    });
  };

  if (confirm('안읽은 알림을 모두 삭제하시겠습니까?\n쪽지는 삭제되지 않습니다.')) {
    confirmHandler();
  }
}

/**
 * Toggle notification dropdown
 */
document.addEventListener('DOMContentLoaded', function() {
  const toggleBtn = document.getElementById('notificationToggleBtn');
  const dropdown = document.getElementById('notificationDropdown');

  if (toggleBtn && dropdown) {
    toggleBtn.addEventListener('click', function(e) {
      e.stopPropagation();
      dropdown.classList.toggle('show');
    });

    // Close dropdown when clicking outside
    document.addEventListener('click', function(e) {
      if (!dropdown.contains(e.target) && !toggleBtn.contains(e.target)) {
        dropdown.classList.remove('show');
      }
    });
  }
});
