
var i = 0;

var progress = document.getElementById("progress");


Next1.onclick = function () {
    Form1.style.left = "-450px";
    Form2.style.left = "40px";
    progress.style.width = "45px";
}

Back1.onclick = function () {
    Form1.style.left = "40px";
    Form2.style.left = "450px";
    progress.style.width = "0px";
}

Next2.onclick = function () {
    i = 0;
    const rbs = document.querySelectorAll('input[name="knowledge_1"]');
    for (const rb of rbs) {
        if (rb.checked) {
            Form2.style.left = "-450px";
            Form3.style.left = "40px";
            progress.style.width = "90px";
            break;
        }
        else if (i === 2) {
            i = 0;
            alert('Please select an answer before you go on.');
            break;
        }
        else {
            i++;
        }
    }
}

Back2.onclick = function () {
    Form2.style.left = "40px";
    Form3.style.left = "450px";
    progress.style.width = "45px";
}

Next3.onclick = function () {
    i = 0;
    const rbs = document.querySelectorAll('input[name="knowledge_2"]');
    for (const rb of rbs) {
        if (rb.checked) {
            Form3.style.left = "-450px";
            Form4.style.left = "40px";
            progress.style.width = "135px";
        }
        else if (i === 2) {
            i = 0;
            alert('Please select an answer before you go on.');
            break;
        }
        else {
            i++;
        }
    }
}

Back3.onclick = function () {
    Form3.style.left = "40px";
    Form4.style.left = "450px";
    progress.style.width = "90px";
}

Next4.onclick = function () {
    i = 0;
    const rbs = document.querySelectorAll('input[name="knowledge_3"]');
    for (const rb of rbs) {
        if (rb.checked) {
            Form4.style.left = "-450px";
            Form5.style.left = "40px";
            progress.style.width = "180px";
            break;
        }
        else if (i === 2) {
            i = 0;
            alert('Please select an answer before you go on.');
            break;
        }
        else {
            i++;
        }
    }
}

Back4.onclick = function () {
    Form4.style.left = "40px";
    Form5.style.left = "450px";
    progress.style.width = "135px";
}

Next5.onclick = function () {
    i = 0;
    const rbs = document.querySelectorAll('input[name="knowledge_4"]');
    for (const rb of rbs) {
        if (rb.checked) {
            Form5.style.left = "-450px";
            Form6.style.left = "40px";
            progress.style.width = "225px";
        }
        else if (i === 2) {
            i = 0;
            alert('Please select an answer before you go on.');
            break;
        }
        else {
            i++;
        }
    }
}

Back5.onclick = function () {
    Form5.style.left = "40px";
    Form6.style.left = "450px";
    progress.style.width = "180px";
}

Next6.onclick = function () {
    i = 0;
    const rbs = document.querySelectorAll('input[name="knowledge_5"]');
    for (const rb of rbs) {
        if (rb.checked) {
            Form6.style.left = "-450px";
            Form7.style.left = "40px";
            progress.style.width = "270px";
        }
        else if (i === 2) {
            i = 0;
            alert('Please select an answer before you go on.');
            break;
        }
        else {
            i++;
        }
    }
}

Back6.onclick = function () {
    Form6.style.left = "40px";
    Form7.style.left = "450px";
    progress.style.width = "225px";
}

Next7.onclick = function () {
    Form7.style.left = "-450px";
    Form8.style.left = "40px";
    progress.style.width = "315px";
}

Back7.onclick = function () {
    Form7.style.left = "40px";
    Form8.style.left = "450px";
    progress.style.width = "270px";
}

Next8.onclick = function () {
    Form8.style.left = "-450px";
    Form9.style.left = "40px";
    progress.style.width = "360px";
}

Back8.onclick = function () {
    Form8.style.left = "40px";
    Form9.style.left = "450px";
    progress.style.width = "315px";
}
