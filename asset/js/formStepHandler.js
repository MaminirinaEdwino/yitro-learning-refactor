let step = 1;

for (let index = 2; index < 7; index++) {
    let elementId = `section${index}`;
    document.getElementById(elementId).style.display = "none";
}
function InitStepView() {
    document.getElementById("step").innerHTML = ""
    for (let index = 1; index < 7; index++) {
        document.getElementById("step").innerHTML += `<div class="step ${index <= step ? "steped" : ""}"></div>`
    }
}
document.getElementById("step-btn").innerHTML = `
<button class="btn btn-primary" onclick="Suivant()">></button>
`
InitStepView()

function Suivant() {
    if (step < 6) {
        let oldelId = `section${step}`
        document.getElementById(oldelId).style.display = "none"
        step++
        let elId = `section${step}`
        document.getElementById(elId).style.display = "block"
        InitStepView()
        document.getElementById("step-btn").innerHTML = `
${step > 1 ? `<button class="btn btn-primary" onclick="Precedent()"> < </button>` : ""}
${step < 6 ? `<button class="btn btn-primary" onclick="Suivant()">></button>` : ""}
`
        window.location.replace("#step")
        checkvalue()
    }
}
function Precedent() {
    if (step > 1) {
        let oldelId = `section${step}`
        document.getElementById(oldelId).style.display = "none"
        step--
        let elId = `section${step}`
        document.getElementById(elId).style.display = "block"
        InitStepView()
        document.getElementById("step-btn").innerHTML = `
${step > 1 ? `<button class="btn btn-primary" onclick="Precedent()"> < </button>` : ""}
${step < 6 ? `<button class="btn btn-primary" onclick="Suivant()">></button>` : ""}
`
        window.location.replace("#step")
        checkvalue()
    }
}