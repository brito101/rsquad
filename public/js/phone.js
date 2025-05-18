$(document).ready(function () {
    $("#telephone").inputmask("(99) 9999-9999");
    $("#cell").inputmask("(99) 99999-9999");
});

$(document).ready(function () {
  $("#phone").inputmask({
    mask: ["(99) 9999-9999", "(99) 99999-9999"],
    keepStatic: true 
  });
});
