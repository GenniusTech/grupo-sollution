function changeClockColor() {
  const colors = ["#4369D7", "#36B9CC", "#1CC88A"];
  const clockText = document.getElementById("clockText");
  let colorIndex = 0;

  setInterval(function () {
      clockText.style.color = colors[colorIndex];
      colorIndex = (colorIndex + 1) % colors.length;
  }, 1000);
}

changeClockColor();
