// Main app page color theme switcher

const topColorElement = document.querySelector("[data-top-color]");

const topColor = (_) => {
  //   const randomColor =
  //     "#" +
  //     Math.floor(Math.random() * 16777215)
  //       .toString(16)
  //       .padStart(6, "0");

  const h = Math.floor(Math.random() * (300 - 80 + 1)) + 80; // hue – tik žalsva zona
  const s = Math.floor(Math.random() * (100 - 60 + 1)) + 60; // saturation – ryškumas
  const l = Math.floor(Math.random() * (70 - 40 + 1)) + 40; // lightness – ne per tamsi / ne per šviesi

  const randomColor = `hsl(${h}, ${s}%, ${l}%)`;

  topColorElement.style.color = randomColor;
};

const topPhraseElement = document.querySelector("[data-top-phrase]");

const getPhrase = (_) => {
  fetch("api-phrases.php")
    .then((response) => response.json())
    .then((data) => {
      topPhraseElement.textContent = data.phrase;
    })
    .catch((error) => {
      console.error("Error fetching phrase:", error);
    });
};

if (topPhraseElement && topColorElement) {
  getPhrase();
  topColor();
  setInterval(() => {
    getPhrase();
    topColor();
  }, 3000);
}
