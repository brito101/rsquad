const button = document.querySelector(".smoothScroll-top");
if (button) {
    document.addEventListener("scroll", () => {
        if (window.scrollY > 100) {
            button.classList.add("show");
        } else {
            button.classList.remove("show");
        }
    });
    button.addEventListener("click", () => {
        window.scroll({
            top: 0,
            behavior: "smooth",
        });
    });
}