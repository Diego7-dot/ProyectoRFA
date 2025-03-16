// Js de inicio de sesion 

// KM = Animacion del icono
document.addEventListener("DOMContentLoaded", function () {
    gsap.from(".icono-rol", {
        opacity: 0,
        y: 15,
        duration: 1,
        stagger: 0.3,
        ease: "power1.out"
    });

    gsap.to(".icono-rol", {
        y: -5,
        repeat: -1,
        duration: 1.5,
        yoyo: true,
        ease: "power1.inOut"
    });
});

