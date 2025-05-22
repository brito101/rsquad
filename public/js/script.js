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
  const ativa = reponse.classList.contains("active");
  question.setAttribute("aria-expanded", active);
}

function eventQuestions(pergunta) {
  pergunta.addEventListener("click", activateQuestion);
}

questions.forEach(eventQuestions);

// Animation
if (window.SimpleAnime) {
  new SimpleAnime();
}
