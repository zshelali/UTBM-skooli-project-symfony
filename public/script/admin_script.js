document.addEventListener("DOMContentLoaded", function () {
    // Select all tabs in the page
    const tabs = document.querySelectorAll("#tabs ul li a");
    // Select the content of each tab
    const contents = document.querySelectorAll("#tabs > div");

    // Hide the tabs, except the first
    contents.forEach((content, index) => {
        content.style.display = index === 0 ? "block" : "none";
    });

    // 4. Ajoute un événement sur chaque onglet
    tabs.forEach(tab => {
        tab.addEventListener("click", function (e) {
            // prevent default behavior of <a> tag
            e.preventDefault();

            // get href attribut
            const targetId = this.getAttribute("href");

            // hide everything
            contents.forEach(content => {
                content.style.display = "none";
            });

            // show only the requested tab
            const targetContent = document.querySelector(targetId);
            if (targetContent) {
                targetContent.style.display = "block";
            }
        });
    });
});
