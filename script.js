'use strict';

var expandControls = document.querySelectorAll('.expand-control');

var hidePopups = function () {
  [].forEach.call(document.querySelectorAll('.expand-list'), function (item) {
    item.classList.add('hidden');
  });
};

document.body.addEventListener('click', hidePopups, true);

[].forEach.call(expandControls, function (item) {
  item.addEventListener('click', function () {
    item.nextElementSibling.classList.toggle('hidden');
  });
});

document.body.addEventListener('click', function (event) {
  var target = event.target;
  var modal = null;

  if (target.classList.contains('open-modal')) {
    var modal_id = target.getAttribute('target');
    modal = document.getElementById(modal_id);

    if (modal) {
      document.body.classList.add('overlay');
      modal.removeAttribute('hidden');
    }
  }

  if (target.classList.contains('modal__close')) {
    modal = target.parentNode;
    modal.setAttribute('hidden', 'hidden');
    document.body.classList.remove('overlay');
  }
});

var $checkbox = document.getElementsByClassName('show_completed')[0];

$checkbox.addEventListener('change', function (event) {
  var el = event.target;                                                   // added by Kotelevets
  var is_checked = +event.target.checked;
  var project = el.getAttribute('value');                                  // added by Kotelevets

  // window.location = '/index.php?show_completed=' + is_checked;          // commented by Kotelevets
  var url = '/index.php?show_completed=' + is_checked + project;           // added by Kotelevets
  window.location = url;                                                   // added by Kotelevets
});

var $taskCheckboxes = document.getElementsByClassName('tasks')[0];

$taskCheckboxes.addEventListener('change', function (event) {
  if (event.target.classList.contains('task__checkbox')) {
    var el = event.target;
    var is_checked = +el.checked;
   
    //var task_id = el.getAttribute('value');                              // commented by Kotelevets
    var link = el.getAttribute('value');                                   // added by Kotelevets

    //var url = '/index.php?task_id=' + task_id + '&check=' + is_checked;  // commented by Kotelevets
    var url = '/index.php' + link + '&check=' + is_checked;                // added by Kotelevets
    window.location = url;
  }
});

flatpickr('#date', {
  enableTime: true,
  dateFormat: "Y-m-d H:i",
  time_24hr: true,
  locale: "ru"
});
