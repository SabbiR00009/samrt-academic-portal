(function () {
  const form = document.getElementById('quizForm');
  const timer = document.getElementById('timer');

  if (!form || !timer) {
    return;
  }

  const rawSeconds = parseInt(form.getAttribute('data-time-seconds'), 10);
  if (Number.isNaN(rawSeconds) || rawSeconds <= 0) {
    timer.textContent = 'No time limit';
    return;
  }

  let secondsLeft = rawSeconds;

  function renderTime() {
    const minutes = Math.floor(secondsLeft / 60);
    const seconds = secondsLeft % 60;
    timer.textContent = minutes + ':' + (seconds < 10 ? '0' : '') + seconds;

    timer.classList.remove('warning', 'danger');
    if (secondsLeft <= 60) {
      timer.classList.add('danger');
    } else if (secondsLeft <= 300) {
      timer.classList.add('warning');
    }
  }

  renderTime();

  const intervalId = setInterval(function () {
    secondsLeft -= 1;
    renderTime();

    if (secondsLeft <= 0) {
      clearInterval(intervalId);
      form.submit();
    }
  }, 1000);
}());
