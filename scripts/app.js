const observer = new IntersectionObserver((entries) => {
    entries.forEach((entry) =>{
        console.log(entry)
        if (entry.isIntersecting){
            entry.target.classList.add('show');
        } else {
            entry.target.classList.remove('show');
        }
    });
}); 

const hiddenElements = document.querySelectorAll('.hidden');
hiddenElements.forEach((el) => observer.observe(el));


//icon as back button
$(document).ready(function () {
    $('.back-icon').click(function () {
        window.location.href = 'index.php';
    });
});

//show password function for show password checkbox
document.getElementById('showPass').addEventListener('change', function() {
    var passwordInput = document.getElementById('passwordInput');
    passwordInput.type = this.checked ? 'text' : 'password';
});

//svg animation on click
/*
const svg = document.getElementById('triangles');
svg.onclick = (e) => {
    const colors =  ['red', 'blue', 'green', 'orange', 'pink', 'violet'];
    const rand = () => colors[Math.floor(Math.random() * colors.length)];
    document.documentElement.style.cssText = `
    --pastelpurple-color: ${rand()};
    --lightblue-color: ${rand()};    `
};

$(document).ready(function () {
    const svg = $('#triangles');
    svg.click(function (){
        const colors =  ['red', 'blue', 'green', 'orange', 'pink', 'violet'];
        const rand = () => colors[Math.floor(Math.random() * colors.length)];
        document.documentElement.style.cssText = `
        --pastelpurple-color: ${rand()};
        --lightblue-color: ${rand()};
        `;
    });
});
*/
