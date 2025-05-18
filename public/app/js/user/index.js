import {userCOntroller} from "./controller.js";
document.addEventListener("DOMContentLoaded", () => {
    document.getElementById("buttonListar").onclick = () => {
        userCOntroller.list();
    }
})