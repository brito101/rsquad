const links = document.querySelectorAll(".header-menu a");

function activateLink(link) {
  const url = location.href;
  const href = link.href;
  if (url.includes(href)) {
    link.classList.add("active");
  }
}

links.forEach(activateLink);

// Questions
const questions = document.querySelectorAll(".questions button");

function activateQuestion(event) {
  const question = event.currentTarget;
  const controls = question.getAttribute("aria-controls");
  const reponse = document.getElementById(controls);

  reponse.classList.toggle("active");
  const active = reponse.classList.contains("active");
  question.setAttribute("aria-expanded", active);
}

function eventQuestions(question) {
  question.addEventListener("click", activateQuestion);
}

questions.forEach(eventQuestions);

// Cheat
const cheats = document.querySelectorAll(".cheats button");

function activateCheat(event) {
  const cheat = event.currentTarget;
  const controls = cheat.getAttribute("aria-controls");
  const reponseCheat = document.getElementsByClassName(controls);
  
  Array.from(reponseCheat).forEach(function (el) {
    el.classList.toggle("active");
  });

  const activeCheat = Array.from(reponseCheat).some(el => el.classList.contains("active"));
  cheat.setAttribute("aria-expanded", activeCheat);
}

function eventCheat(cheat) {
  cheat.addEventListener("click", activateCheat);
}

cheats.forEach(eventCheat);

// Animation
if (window.SimpleAnime) {
  new SimpleAnime();
}
