const el = document.querySelector("form");

function formSent(resp) {
  if (resp.ok) {
    el.innerHTML =
      "<p class='font-2-l' style='grid-column: 1/-1; padding: 1rem; border-radius: 4px; background: #f7f7f7;'><span style='color: #317A00;'>Mensagem enviada</span>, em breve entraremos em contato. Geralmente respondemos em 24 horas.</p>";
  } else {
    el.innerHTML =
      "<p class='font-2-l' style='grid-column: 1/-1; padding: 1rem; border-radius: 4px; background: #f7f7f7;'><span style='color: #E00000;'>Erro no envio</span>, você pode enviar diretamente para o nosso email em: contato@bikcraft.net</p>";
  }
}

function sendForm(event) {
  event.preventDefault();
  const btn = document.querySelector("form button");
  btn.disabled = true;
  btn.innerText = "Enviando...";

  const data = new FormData(el);

  fetch("./send.php", {
    method: "POST",
    body: data,
  }).then(formSent);
}

el.addEventListener("submit", sendForm);
