<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Alchemy</title>

    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@500;700&display=swap" rel="stylesheet">

    <style>
        * {
            box-sizing: border-box;

            /* I disabled text and element selection here */
            -webkit-touch-callout: none;
            -webkit-user-select: none;
            -khtml-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
        }

        /* I disabled image dragging here */
        img {
            -webkit-user-drag: none;
            user-drag: none;
        }

        body {
            margin: 0;
            font-family: 'Cinzel', serif;
            background: radial-gradient(circle at top, #f6e7d7, #e7c9a3);
            color: #2b1d14;
            overflow: hidden;
        }

        .container {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
            text-align: center;
        }

        /* ===== HEADING ===== */
        h1 {
            font-size: 60px;
            margin-bottom: 20px;
            letter-spacing: 2px;
            color: #3b2418;
            text-shadow:
                0 2px 0 #00000030,
                0 0 10px #ffffff40,
                0 0 20px #caa46a;
        }

        /* ===== ENLARGED LOGO ===== */
        img {
            width: 420px;
            margin-bottom: 30px;
            border-radius: 16px;
            filter: drop-shadow(0 10px 20px rgba(0,0,0,0.3));
            transition: transform 0.3s ease;
        }

        img:hover {
            transform: scale(1.05);
        }

        /* ===== BUTTON ===== */
        .alchemy-btn {
            position: relative;
            overflow: hidden;
            padding: 16px 32px;
            font-size: 20px;
            background: linear-gradient(145deg, #8b6f4e, #b59a7a);
            color: #fff;
            border: 1px solid #ffffff30;
            border-radius: 12px;
            cursor: pointer;
            transition: transform 0.25s ease, box-shadow 0.25s ease;
            z-index: 10;
            font-family: 'Cinzel', serif;
            box-shadow: 0 10px 25px rgba(0,0,0,0.25);
        }

        .alchemy-btn:hover {
            transform: scale(1.12);
            box-shadow: 0 0 25px #d6b37a;
        }

        /* ===== SHINE EFFECT ===== */
        .alchemy-btn::before {
            content: "";
            position: absolute;
            top: 0;
            left: -150%;
            width: 60%;
            height: 100%;
            background: linear-gradient(
                120deg,
                transparent,
                rgba(255,255,255,0.6),
                transparent
            );
            transform: skewX(-20deg);
        }

        .alchemy-btn:hover::before {
            animation: shine 0.8s ease;
        }

        @keyframes shine {
            0% { left: -150%; }
            100% { left: 150%; }
        }

        /* ===== button bubbles ===== */
        .bubble {
            position: absolute;
            bottom: 0;
            width: 6px;
            height: 6px;
            border-radius: 50%;
            pointer-events: none;
            animation: rise linear forwards;
        }

        @keyframes rise {
            0% { transform: translateY(0) scale(0.5); opacity: 0; }
            10% { opacity: 1; }
            100% { transform: translateY(-120px) scale(1.4); opacity: 0; }
        }

        /* ===== global bubbles ===== */
        .global-bubble {
            position: fixed;
            bottom: 0;
            width: 22px;
            height: 22px;
            border-radius: 50%;
            pointer-events: none;
            animation: globalRise linear forwards;
            z-index: 1;
        }

        @keyframes globalRise {
            0% {
                transform: translateY(0) scale(0.6);
                opacity: 0;
            }
            10% { opacity: 1; }
            100% {
                transform: translateY(-110vh) translateX(60px) scale(2.2);
                opacity: 0;
            }
        }
    </style>
</head>

<body>

<div class="container">

    <h1>Welcome</h1>

    <img src="{{ asset('images/Alchemy.png') }}" alt="Alchemy">

    <a href="/start">
        <button class="alchemy-btn" id="alchemyBtn">
            Start Brewing
        </button>
    </a>

</div>

<script>
// I disabled copying and right-clicking here
document.addEventListener('contextmenu', event => event.preventDefault());
document.addEventListener('dragstart', event => event.preventDefault());
document.addEventListener('selectstart', event => event.preventDefault());
document.addEventListener('copy', event => event.preventDefault());

const btn = document.getElementById("alchemyBtn");

btn.addEventListener("mouseenter", () => {

    const interval = setInterval(() => {
        createButtonBubble();
        createGlobalBubble();
    }, 90);

    setTimeout(() => {
        clearInterval(interval);
    }, 1600);
});

function getRandomBubbleColor(baseOpacity) {
    // 👇 I changed this: brightness is now from 220 to 255 (previously from 180)
    // This makes the bubbles lighter and reduces the contrast between them
    const brightness = Math.floor(Math.random() * 36) + 220;

    // I left a slight variation in opacity
    const opacityVariation = (Math.random() * 0.2 - 0.1);
    const finalOpacity = Math.max(0.1, Math.min(1, baseOpacity + opacityVariation));

    return `rgba(${brightness}, ${brightness}, ${brightness}, ${finalOpacity})`;
}

function createButtonBubble() {
    const bubble = document.createElement("span");
    bubble.classList.add("bubble");

    bubble.style.left = Math.random() * 100 + "%";

    const size = Math.random() * 6 + 3;
    bubble.style.width = size + "px";
    bubble.style.height = size + "px";

    bubble.style.animationDuration = (Math.random() * 1 + 1) + "s";

    bubble.style.background = getRandomBubbleColor(0.75);

    btn.appendChild(bubble);

    setTimeout(() => bubble.remove(), 2000);
}

function createGlobalBubble() {
    const bubble = document.createElement("span");
    bubble.classList.add("global-bubble");

    bubble.style.left = Math.random() * window.innerWidth + "px";

    const size = Math.random() * 35 + 20;
    bubble.style.width = size + "px";
    bubble.style.height = size + "px";

    bubble.style.animationDuration = (Math.random() * 3 + 3) + "s";

    bubble.style.background = getRandomBubbleColor(0.5);

    document.body.appendChild(bubble);

    setTimeout(() => bubble.remove(), 6000);
}
</script>

</body>
</html>
