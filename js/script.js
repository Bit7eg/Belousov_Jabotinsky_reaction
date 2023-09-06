window.onload = () => {
    let home_button = document.getElementById("home-button");
    let description_button = document.getElementById("description-button");
    let report_button = document.getElementById("report-button");
    let current_page = document.getElementsByTagName("main").item(0);

    home_button.onclick = async () => {
        let home_page = await (await fetch("https://" + window.location.host + "/pages/app.php")).text();
        home_page = new DOMParser().parseFromString(home_page, "text/html");
        home_page = home_page.getElementsByTagName("main").item(0);

        current_page.parentElement.replaceChild(home_page, current_page);
        current_page = document.getElementsByTagName("main").item(0);
    }

    description_button.onclick = async () => {
        let description_page = await (await fetch("https://" + window.location.host + "/pages/description.php")).text();
        description_page = new DOMParser().parseFromString(description_page, "text/html");
        description_page = description_page.getElementsByTagName("main").item(0);

        current_page.parentElement.replaceChild(description_page, current_page);
        current_page = document.getElementsByTagName("main").item(0);
    }

    report_button.onclick = async () => {
        let report_page = await (await fetch("https://" + window.location.host + "/pages/report.php")).text();
        report_page = new DOMParser().parseFromString(report_page, "text/html");
        report_page = report_page.getElementsByTagName("main").item(0);

        current_page.parentElement.replaceChild(report_page, current_page);
        current_page = document.getElementsByTagName("main").item(0);
    }
}