<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Alchemy Laboratory</title>

    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;600;700&display=swap" rel="stylesheet">

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
            margin: 0; padding: 40px; min-height: 100vh; overflow-x: hidden;
            font-family: 'Cinzel', serif; background: #ead8c1; color: #e6daca;
        }

        h1 { text-align: center; font-size: 60px; margin-bottom: 0px; letter-spacing: 2px; color: #140c01; text-shadow: 0 2px 0 rgba(0,0,0,0.15), 0 6px 18px rgba(0,0,0,0.25), 0 12px 35px rgba(0,0,0,0.15); }

        .filters { display: flex; justify-content: center; flex-wrap: wrap; gap: 16px; margin-top: 0px; margin-bottom: 50px; }
        .filter-btn { padding: 14px 28px; border: 1px solid rgba(255, 255, 255, 0.15); border-radius: 16px; background: linear-gradient(145deg, #7a5a3a, #4b3322); color: #f3e6d3; font-size: 16px; font-weight: bold; cursor: pointer; transition: 0.25s; box-shadow: 0 4px 10px rgba(0,0,0,0.35); }
        .filter-btn:hover { transform: translateY(-3px) scale(1.05); box-shadow: 0 6px 15px rgba(122, 90, 58, 0.5); }
        .filter-btn.active { background: linear-gradient(145deg, #b07a4a, #6a452b); color: #fff; border-color: rgba(255, 255, 255, 0.25); box-shadow: 0 0 15px rgba(176, 122, 74, 0.5); }

        .ingredients-grid { display: grid; grid-template-columns: repeat(6, 1fr); gap: 22px; }
        .ingredient { position: relative; overflow: hidden; padding: 22px; padding-right: 60px; border-radius: 22px; cursor: pointer; color: white; border: 2px solid rgba(255,255,255,0.15); backdrop-filter: blur(8px); box-shadow: 0 10px 25px rgba(0,0,0,0.4); display: flex; flex-direction: column; gap: 10px; opacity: 0; transform: translateY(25px); transition: 0.4s ease; }
        .ingredient.visible { opacity: 1; transform: translateY(0); }
        .ingredient:hover { transform: scale(1.06) translateY(-4px); box-shadow: 0 15px 30px rgba(0,0,0,0.6); }
        .ingredient.clicked { animation: magicPulse 0.45s ease; }
        @keyframes magicPulse { 0% { transform: scale(1); } 50% { transform: scale(1.12); } 100% { transform: scale(1); } }

        .plant { background: linear-gradient(145deg, #4ca65b, #275a31); }
        .animal { background: linear-gradient(145deg, #b14b4b, #611c1c); }
        .mineral { background: linear-gradient(145deg, #557fd6, #233c6d); }

        .ingredient-name { font-size: 18px; font-weight: 700; line-height: 1.2; }
        .ingredient-essence { font-size: 13px; opacity: 0.9; letter-spacing: 1px; }

        .counter { position: absolute; top: 12px; right: 12px; width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 16px; font-weight: bold; background: rgba(255,255,255,0.18); border: 1px solid rgba(255,255,255,0.3); backdrop-filter: blur(6px); }

        @media (max-width: 1400px) { .ingredients-grid { grid-template-columns: repeat(4, 1fr); } }
        @media (max-width: 900px) { .ingredients-grid { grid-template-columns: repeat(2, 1fr); } }

        .title-wrap { display: flex; align-items: center; justify-content: center; gap: 20px; margin-bottom: 12px; }
        .snake { height: 60px; width: auto; opacity: 0.95; }
        .snake.left { transform: scaleX(-1); }

        .crafting-section { display: flex; justify-content: center; align-items: flex-start; gap: 80px; margin-top: 140px; padding-bottom: 140px; max-width: 1400px; margin-left: auto; margin-right: auto; }
        .crafting-left, .crafting-right { display: flex; flex-direction: column; align-items: center; flex: 1; }
        .section-title { font-size: 40px; color: #140c01; margin: 0 0 110px 0; text-shadow: 0 2px 0 rgba(0,0,0,0.15), 0 6px 18px rgba(0,0,0,0.25); letter-spacing: 2px; }

        .kettle-container { display: flex; flex-direction: column; align-items: center; width: 100%; }
        .kettle-wrap { position: relative; width: 420px; height: 420px; display: flex; align-items: center; justify-content: center; }
        .kettle-wrap.crafted { animation: craftPulse 0.5s ease-out; }
        @keyframes craftPulse { 0% { transform: scale(1); } 50% { transform: scale(1.05); } 100% { transform: scale(1); } }
        .kettle-wrap.crafted .essence { animation: vibrateEssence 0.2s infinite, essenceGlow 0.5s ease-out; }
        @keyframes essenceGlow { 0% { box-shadow: 0 6px 15px rgba(0, 0, 0, 0.5); } 50% { box-shadow: 0 0 35px rgb(68, 157, 83), inset 0 0 15px rgba(255, 255, 255, 0.3); } 100% { box-shadow: 0 6px 15px rgba(0, 0, 0, 0.5); } }

        .kettle-img { width: 400px; }

        /* I set up the dice roll result styling here */
        .dice-result {
            position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%) scale(0);
            z-index: 20; pointer-events: none; opacity: 0; font-weight: bold; font-size: 72px; color: #fff;
            --glow-color: #ffffff;
            --glow-dark: #888888;
            text-shadow: 0 0 10px var(--glow-dark), 0 0 20px var(--glow-color), 0 0 40px var(--glow-color), 0 0 60px var(--glow-color);
        }
        .dice-result.big-dice {
            position: fixed; top: 50%; left: 50%; width: 100vw; display: flex; justify-content: center; gap: 40px; font-size: 200px;
        }
        .dice-result.show { animation: dicePop 5.5s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards; }
        @keyframes dicePop {
            0% { transform: translate(-50%, -50%) scale(0); opacity: 0; }
            5% { transform: translate(-50%, -50%) scale(1.3); opacity: 1; }
            10% { transform: translate(-50%, -50%) scale(1); opacity: 1; }
            85% { transform: translate(-50%, -50%) scale(1); opacity: 1; filter: drop-shadow(0 0 15px var(--glow-color)); }
            100% { transform: translate(-50%, -50%) scale(1.5); opacity: 0; filter: blur(10px); }
        }

        .essence { position: absolute; width: 90px; height: 90px; border-radius: 50%; display: flex; flex-direction: column; justify-content: center; align-items: center; font-size: 13px; font-weight: bold; color: white; text-align: center; top: 50%; left: 50%; box-shadow: 0 6px 15px rgba(0, 0, 0, 0.5); z-index: 10; --intensity: 0; animation: vibrateEssence 0.2s infinite; }

        /* My base shake animation based on intensity */
        @keyframes vibrateEssence { 0% { translate: 0px 0px; } 25% { translate: calc(var(--intensity) * -1px) calc(var(--intensity) * 1px); } 50% { translate: calc(var(--intensity) * 1px) calc(var(--intensity) * -1px); } 75% { translate: calc(var(--intensity) * 1px) calc(var(--intensity) * 1px); } 100% { translate: 0px 0px; } }

        /* Heavy shake effect for the drain button */
        .essence.shake { animation: shakeEssence 0.5s ease-in-out; }
        @keyframes shakeEssence {
            0%   { translate: 0px 0px; rotate: 0deg; }
            20%  { translate: -3px 2px; rotate: -5deg; }
            40%  { translate: 3px -2px; rotate: 5deg; }
            60%  { translate: -2px -3px; rotate: -5deg; }
            80%  { translate: 2px 3px; rotate: 5deg; }
            100% { translate: 0px 0px; rotate: 0deg; }
        }

        .e-ferrum { background: linear-gradient(145deg, #939ea6, #636b71); transform: translate(-50%, -50%) rotate(0deg) translateY(-270px) rotate(0deg); }
        .e-gelum  { background: linear-gradient(145deg, #96c8d7, #6392a0); transform: translate(-50%, -50%) rotate(40deg) translateY(-270px) rotate(-40deg); }
        .e-mutare { background: linear-gradient(145deg, #a37be6, #6a46ad); transform: translate(-50%, -50%) rotate(80deg) translateY(-270px) rotate(-80deg); }
        .e-vitae  { background: linear-gradient(145deg, #60c487, #3b8757); transform: translate(-50%, -50%) rotate(120deg) translateY(-270px) rotate(-120deg); }
        .e-noxa   { background: linear-gradient(145deg, #a35b84, #693752); transform: translate(-50%, -50%) rotate(160deg) translateY(-270px) rotate(-160deg); }
        .e-terrum { background: linear-gradient(145deg, #866d5b, #544336); transform: translate(-50%, -50%) rotate(200deg) translateY(-270px) rotate(-200deg); }
        .e-mentis { background: linear-gradient(145deg, #6687ff, #3753b5); transform: translate(-50%, -50%) rotate(240deg) translateY(-270px) rotate(-240deg); }
        .e-ventus { background: linear-gradient(145deg, #87cfff, #528eb5); transform: translate(-50%, -50%) rotate(280deg) translateY(-270px) rotate(-280deg); }
        .e-ignis  { background: linear-gradient(145deg, #ff8163, #ba4b33); transform: translate(-50%, -50%) rotate(320deg) translateY(-270px) rotate(-320deg); }

        .slit-btn, .brew-btn { padding: 18px 70px; color: white; font-family: 'Cinzel', serif; font-size: 18px; font-weight: bold; border: 1px solid rgba(255, 255, 255, 0.2); border-radius: 12px; cursor: pointer; transition: 0.3s ease; }
        .slit-btn { margin-top: 120px; background: linear-gradient(145deg, #E66A4E, #b83a24); box-shadow: 0 6px 15px rgba(230, 106, 78, 0.4); }
        .slit-btn:hover { transform: scale(1.05); box-shadow: 0 6px 25px rgba(230, 106, 78, 0.7); }

        .brew-btn { margin-top: 70px; background: linear-gradient(145deg, #4ca65b, #275a31); box-shadow: 0 6px 15px rgba(76, 166, 91, 0.4); opacity: 0.4; pointer-events: none; }
        .brew-btn.active { opacity: 1; pointer-events: auto; }
        .brew-btn:hover { transform: scale(1.05); box-shadow: 0 6px 25px rgba(76, 166, 91, 0.7); }

        .recipes-list {
            display: flex;
            flex-direction: column;
            gap: 16px;
            width: 100%;
            max-width: 535px;
            height: 540px;
            overflow-y: auto;
            padding-right: 12px;
            padding-left: 15px;
            margin-left: -15px;

            -webkit-mask-image: linear-gradient(to bottom, transparent 0%, black 1%, black 97%, transparent 100%), linear-gradient(to right, transparent 0%, black 1%, black 100%);
            -webkit-mask-composite: source-in;
            mask-image: linear-gradient(to bottom, transparent 0%, black 1%, black 97%, transparent 100%), linear-gradient(to right, transparent 0%, black 1%, black 100%);
            mask-composite: intersect;
        }
        .recipes-list::-webkit-scrollbar { width: 8px; }
        .recipes-list::-webkit-scrollbar-track { background: rgba(0, 0, 0, 0.05); border-radius: 4px; }
        .recipes-list::-webkit-scrollbar-thumb { background: #7a5a3a; border-radius: 4px; }

        .recipe-card { position: relative; background: linear-gradient(145deg, #7a5a3a, #4b3322); padding: 20px; border-radius: 16px; border: 2px solid rgba(255, 255, 255, 0.15); color: white; transition: 0.3s ease; cursor: pointer; flex-shrink: 0; }
        .recipe-card:hover { transform: translateY(-3px) scale(1.02); border-color: rgba(255, 255, 255, 0.4); }
        .recipe-card.selected { background: linear-gradient(145deg, #4b6b42, #2f4728); border-color: #60c487; box-shadow: 0 0 20px rgba(96, 196, 135, 0.5); transform: scale(1.02); }
        .recipe-card h3 { margin: 0 0 10px 0; font-size: 20px; color: #f3e6d3; letter-spacing: 1px; padding-right: 90px; }
        .recipe-reqs { font-size: 14px; opacity: 0.85; line-height: 1.5; }

        .recipe-counter { position: absolute; top: 20px; right: 20px; width: 32px; height: 32px; border-radius: 50%; background: #fff; color: #2f4728; display: flex; align-items: center; justify-content: center; font-weight: bold; font-size: 16px; box-shadow: 0 4px 10px rgba(0,0,0,0.3); pointer-events: none; }
        .recipe-minus { position: absolute; top: 20px; right: 60px; width: 32px; height: 32px; border-radius: 50%; background: #E66A4E; color: white; display: flex; align-items: center; justify-content: center; font-weight: bold; font-size: 22px; line-height: 1; cursor: pointer; transition: 0.2s; box-shadow: 0 4px 10px rgba(0,0,0,0.3); }
        .recipe-minus:hover { transform: scale(1.1); background: #ff7e60; }
        .empty-recipes-msg { color: #4b3322; text-align: center; font-weight: bold; margin-top: 20px; font-size: 18px; }

        @media (max-width: 1000px) { .crafting-section { flex-direction: column; align-items: center; gap: 100px; } .crafting-right { align-items: center; } }


        /* My crafting history styles */
        .history-btn {
            position: fixed;
            bottom: 30px;
            right: 30px;
            width: 65px;
            height: 65px;
            border-radius: 50%;
            background: linear-gradient(145deg, #8d664c, #4b3427);
            border: 2px solid rgba(255, 255, 255, 0.15);
            color: #fff;
            font-size: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: 0 6px 15px rgba(0,0,0,0.6), inset 0 0 15px rgba(255,255,255,0.05);
            z-index: 100;

            /* I hid the button initially and set up a smooth transition for scrolling */
            opacity: 0;
            pointer-events: none;
            transform: translateY(20px) scale(0.9);
            transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }

        /* I use this class via JavaScript when the user scrolls past the threshold */
        .history-btn.visible {
            opacity: 1;
            pointer-events: auto;
            transform: translateY(0) scale(1);
        }

        .history-btn.visible:hover {
            transform: scale(1.1) translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.8), 0 0 15px rgba(176, 122, 74, 0.4);
            border-color: rgba(176, 122, 74, 0.5);
        }

        .history-modal-overlay {
            position: fixed;
            top: 0; left: 0; width: 100vw; height: 100vh;
            background: rgba(0, 0, 0, 0.8);
            backdrop-filter: blur(8px);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 1000;
            opacity: 0;
            pointer-events: none;
            transition: 0.3s ease;
        }
        .history-modal-overlay.active {
            opacity: 1;
            pointer-events: auto;
        }

        .history-modal {
            background: linear-gradient(145deg, #4b3322, #2b1f17);
            border: 2px solid rgba(255, 255, 255, 0.15);
            border-radius: 20px;
            padding: 35px 40px;
            width: 90%;
            max-width: 500px;
            max-height: 80vh;
            box-shadow: 0 20px 60px rgba(0,0,0,0.8), inset 0 0 30px rgba(0,0,0,0.5);
            display: flex;
            flex-direction: column;
            transform: translateY(30px) scale(0.95);
            transition: 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            position: relative;
        }
        .history-modal-overlay.active .history-modal {
            transform: translateY(0) scale(1);
        }

        .history-close-btn {
            position: absolute;
            top: 15px;
            right: 20px;
            background: none;
            border: none;
            color: #f3e6d3;
            font-size: 36px;
            cursor: pointer;
            transition: 0.2s;
            line-height: 1;
            opacity: 0.7;
        }
        .history-close-btn:hover {
            opacity: 1;
            color: #e64a4a;
            transform: scale(1.1);
        }

        .history-list-container {
            overflow-y: auto;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            gap: 12px;
            padding-right: 10px;
            margin-top: 20px;
        }
        .history-list-container::-webkit-scrollbar { width: 6px; }
        .history-list-container::-webkit-scrollbar-track { background: rgba(0, 0, 0, 0.2); border-radius: 4px; }
        .history-list-container::-webkit-scrollbar-thumb { background: #b07a4a; border-radius: 4px; }

        .history-item {
            background: rgba(0, 0, 0, 0.3);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            padding: 15px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .history-item-recipes {
            display: flex;
            flex-direction: column;
            gap: 4px;
            color: #f3e6d3;
            font-size: 15px;
        }
        .history-roll {
            font-size: 26px;
            font-weight: bold;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 55px; height: 55px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        .history-empty {
            text-align: center;
            color: #a08c77;
            font-size: 18px;
            margin-top: 30px;
            font-style: italic;
        }
    </style>
</head>

<body>

<div class="title-wrap">
    <img src="/images/snake_right.png" class="snake left" alt="snake" draggable="false">
    <h1>Alchemy Laboratory</h1>
    <img src="/images/snake_right.png" class="snake right" alt="snake" draggable="false">
</div>

<div class="filters">
    <button class="filter-btn active" onclick="filterRarity('all', this)">All</button>
    <button class="filter-btn" onclick="filterRarity('common', this)">Common</button>
    <button class="filter-btn" onclick="filterRarity('rare', this)">Rare</button>
    <button class="filter-btn" onclick="filterRarity('exotic', this)">Exotic</button>
</div>

<div class="ingredients-grid">
    @foreach($ingredients as $ingredient)
        <div class="ingredient {{ $ingredient['origin'] }}"
            data-rarity="{{ $ingredient['rarity'] }}"
            data-essences='@json($ingredient["essences"])'
            onclick="increaseCount(this)">

            <div class="counter">0</div>
            <div class="ingredient-name">{{ $ingredient['name'] }}</div>
            <div class="ingredient-essence">{{ implode(', ', array_map(fn($e) => substr($e, 0, 2), $ingredient['essences'])) }}</div>
        </div>
    @endforeach
</div>

<div class="crafting-section">
    <div class="crafting-left">
        <h2 class="section-title">Brewing</h2>
        <div class="kettle-container">
            <div class="kettle-wrap">
                <img src="/images/kett.png" class="kettle-img" draggable="false">
                <div id="diceResult" class="dice-result"></div>

                <div class="essence e-ferrum"><span id="Ferrum" style="font-size: 11px; margin-bottom: 2px;">0 0! 0!!</span>Ferrum</div>
                <div class="essence e-vitae"><span id="Vitae" style="font-size: 11px; margin-bottom: 2px;">0 0! 0!!</span>Vitae</div>
                <div class="essence e-noxa"><span id="Noxa" style="font-size: 11px; margin-bottom: 2px;">0 0! 0!!</span>Noxa</div>
                <div class="essence e-mentis"><span id="Mentis" style="font-size: 11px; margin-bottom: 2px;">0 0! 0!!</span>Mentis</div>
                <div class="essence e-ventus"><span id="Ventus" style="font-size: 11px; margin-bottom: 2px;">0 0! 0!!</span>Ventus</div>
                <div class="essence e-ignis"><span id="Ignis" style="font-size: 11px; margin-bottom: 2px;">0 0! 0!!</span>Ignis</div>
                <div class="essence e-gelum"><span id="Gelum" style="font-size: 11px; margin-bottom: 2px;">0 0! 0!!</span>Gelum</div>
                <div class="essence e-mutare"><span id="Mutare" style="font-size: 11px; margin-bottom: 2px;">0 0! 0!!</span>Mutare</div>
                <div class="essence e-terrum"><span id="Terrum" style="font-size: 11px; margin-bottom: 2px;">0 0! 0!!</span>Terrum</div>
            </div>
            <button class="slit-btn" onclick="resetAllAlchemy()">DRAIN</button>
        </div>
    </div>

    <div class="crafting-right">
        <h2 class="section-title" style="margin-bottom: 40px;">Recipes</h2>
        <div class="recipes-list" id="recipes-container"></div>
        <button class="brew-btn" id="brewBtn" onclick="brewRecipe()">BREW</button>
    </div>
</div>

<button class="history-btn" id="historyBtn" title="Crafting History">❔</button>

<div class="history-modal-overlay" id="historyModalOverlay">
    <div class="history-modal">
        <button class="history-close-btn" id="closeHistoryBtn">&times;</button>
        <h2 class="section-title" style="margin: 0; font-size: 32px; color: #f3e6d3; text-shadow: none; text-align: center;">Brewing Journal</h2>
        <div class="history-list-container" id="historyList"></div>
    </div>
</div>

<script type="application/json" id="laravel-recipes-data">
    {!! json_encode($recipes) !!}
</script>

<script>
// I'm reading recipe data from a secure HTML container
const recipesData = JSON.parse(document.getElementById('laravel-recipes-data').textContent || '[]');

const baseEssencesList = ["Ferrum", "Vitae", "Noxa", "Mentis", "Ventus", "Ignis", "Gelum", "Mutare", "Terrum"];
let essenceState = {};
baseEssencesList.forEach(e => { essenceState[e] = { common: 0, rare: 0, exotic: 0 }; });

const cauldronRarities = { common: 0, rare: 0, exotic: 0 };
let totalIngredientsAdded = 0;
let selectedRecipes = {};

// Array where I store the crafting history
let craftingHistory = [];

function parseEssence(eStr) {
    if (eStr.endsWith("!!")) return { name: eStr.replace("!!", ""), type: "exotic" };
    if (eStr.endsWith("!")) return { name: eStr.replace("!", ""), type: "rare" };
    return { name: eStr, type: "common" };
}

function updateEssenceUIText(name) {
    let st = essenceState[name];
    let el = document.getElementById(name);
    if (el) { el.innerText = `${st.common} ${st.rare}! ${st.exotic}!!`; }
}

function getAvailableEssences() {
    let avail = JSON.parse(JSON.stringify(essenceState));
    for (let id in selectedRecipes) {
        let count = selectedRecipes[id];
        let recipe = recipesData.find(r => r.id == id);
        for(let i = 0; i < count; i++) {
            recipe.essences.forEach(rawE => {
                let p = parseEssence(rawE);
                avail[p.name][p.type]--;
            });
        }
    }
    return avail;
}

function canCraftOneMore(recipe, avail) {
    let reqs = {};
    recipe.essences.forEach(rawE => {
        let p = parseEssence(rawE);
        if (!reqs[p.name]) reqs[p.name] = { common: 0, rare: 0, exotic: 0 };
        reqs[p.name][p.type]++;
    });

    for (let name in reqs) {
        if (!avail[name]) return false;
        if (avail[name].common < reqs[name].common) return false;
        if (avail[name].rare < reqs[name].rare) return false;
        if (avail[name].exotic < reqs[name].exotic) return false;
    }
    return true;
}

function meetsRarityCondition(recipe) {
    if (recipe.rarity === 'exotic') {
        if (cauldronRarities.common > 0 || cauldronRarities.rare > 0) return false;
    } else if (recipe.rarity === 'rare') {
        if (cauldronRarities.common > 0) return false;
    }
    return true;
}

function increaseCount(card) {
    let counter = card.querySelector('.counter');
    counter.innerText = parseInt(counter.innerText) + 1;

    let essences = JSON.parse(card.dataset.essences);
    essences.forEach(e => addEssence(e));

    let rarity = card.dataset.rarity;
    if (cauldronRarities[rarity] !== undefined) { cauldronRarities[rarity]++; }
    totalIngredientsAdded++;

    card.classList.remove('clicked');
    void card.offsetWidth;
    card.classList.add('clicked');

    updateRecipes();
}

function filterRarity(rarity, button) {
    document.querySelectorAll('.filter-btn').forEach(btn => btn.classList.remove('active'));
    button.classList.add('active');

    document.querySelectorAll('.ingredient').forEach(card => {
        card.style.display = (rarity === 'all' || card.dataset.rarity === rarity) ? 'flex' : 'none';
    });
    setupObserver();
}

let observer;
function setupObserver() {
    if (observer) observer.disconnect();
    observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            const el = entry.target;
            if (entry.isIntersecting) { el.classList.add('visible'); }
            else { el.classList.remove('visible'); }
        });
    }, { threshold: 0.15, rootMargin: "150px 0px" });
    document.querySelectorAll('.ingredient').forEach(el => observer.observe(el));
}

function addEssence(rawName) {
    let parsed = parseEssence(rawName);
    let name = parsed.name;
    let type = parsed.type;

    if (!essenceState[name]) return;

    essenceState[name][type]++;
    updateEssenceUIText(name);

    let totalOfThis = essenceState[name].common + essenceState[name].rare + essenceState[name].exotic;
    let maxIntensity = 3;
    let currentIntensity = totalOfThis * 0.3;
    let finalIntensity = Math.min(currentIntensity, maxIntensity);

    let circleClass = '.e-' + name.toLowerCase();
    let circleEl = document.querySelector(circleClass);
    if (circleEl) {
        circleEl.style.setProperty('--intensity', finalIntensity);
    }
}

function resetAllAlchemy() {
    document.querySelectorAll('.essence').forEach(el => {
        el.classList.remove('shake');
        void el.offsetWidth;
        el.classList.add('shake');

        setTimeout(() => {
            el.classList.remove('shake');
        }, 500);
    });

    baseEssencesList.forEach(k => {
        essenceState[k] = { common: 0, rare: 0, exotic: 0 };
        updateEssenceUIText(k);
        let circleEl = document.querySelector('.e-' + k.toLowerCase());
        if(circleEl) {
            circleEl.style.setProperty('--intensity', 0);
        }
    });

    document.querySelectorAll('.counter').forEach(c => c.innerText = 0);
    cauldronRarities.common = 0; cauldronRarities.rare = 0; cauldronRarities.exotic = 0;
    totalIngredientsAdded = 0;
    selectedRecipes = {};

    let diceEl = document.getElementById('diceResult');
    if (diceEl) diceEl.classList.remove('show');

    updateRecipes();
}

window.decrementRecipe = function(id, event) {
    event.stopPropagation();
    if (selectedRecipes[id] > 0) {
        selectedRecipes[id]--;
        if (selectedRecipes[id] === 0) delete selectedRecipes[id];
        updateRecipes();
    }
};

function updateRecipes() {
    const container = document.getElementById('recipes-container');
    container.innerHTML = '';

    if (totalIngredientsAdded === 0) selectedRecipes = {};
    let avail = getAvailableEssences();

    let displayList = [];

    // I'm checking if any recipe is already selected
    let activeRecipeId = Object.keys(selectedRecipes)[0];

    // ===== 👇 I changed the recipe filtering logic here 👇 =====
    recipesData.forEach(recipe => {
        let isVisible = false;
        let baseRarity = meetsRarityCondition(recipe);

        let sharesEssence = false;
        recipe.essences.forEach(rawE => {
            let p = parseEssence(rawE);
            if (essenceState[p.name][p.type] > 0) {
                sharesEssence = true;
            }
        });

        if (totalIngredientsAdded === 0) {
            isVisible = true; // If the cauldron is empty, I show everything
        } else if (sharesEssence && baseRarity) {
            isVisible = true; // If there's an essence match, I show the recipe
        }

        if (isVisible) {
            let currentCount = selectedRecipes[recipe.id] || 0;
            let isSelected = currentCount > 0;

            // I check if there are enough essences
            let canAddMore = (totalIngredientsAdded > 0) && baseRarity && canCraftOneMore(recipe, avail);

            // If ANOTHER recipe is already selected, I prevent adding this one
            if (activeRecipeId && activeRecipeId != recipe.id) {
                canAddMore = false;
            }

            displayList.push({
                recipe: recipe,
                isSelected: isSelected,
                canAddMore: canAddMore,
                currentCount: currentCount
            });
        }
    });

    // MY SORTING LOGIC:
    // 1. Selected recipes go to the very top
    // 2. Recipes ready to craft go right below them
    // 3. Everything else goes to the bottom, sorted by ID
    displayList.sort((a, b) => {
        if (a.isSelected !== b.isSelected) return a.isSelected ? -1 : 1;
        if (a.canAddMore !== b.canAddMore) return a.canAddMore ? -1 : 1;
        return a.recipe.id - b.recipe.id;
    });

    // I render the sorted list here
    displayList.forEach(item => {
        const recipe = item.recipe;
        const currentCount = item.currentCount;
        const canAddMore = item.canAddMore;

        const card = document.createElement('div');
        card.className = 'recipe-card';

        if (currentCount > 0) { card.classList.add('selected'); }

        // I make the card semi-transparent if it can't be added and nothing is selected
        if (!canAddMore && currentCount === 0 && totalIngredientsAdded > 0) {
            card.style.opacity = '0.4';
            card.style.cursor = 'default';
        }

        let controls = '';
        if (currentCount > 0) {
            controls = `
                <div class="recipe-minus" onclick="decrementRecipe(${recipe.id}, event)">−</div>
                <div class="recipe-counter">${currentCount}</div>
            `;
        }

        let rarityLabel = recipe.rarity === 'common' ? 'Common' : recipe.rarity === 'rare' ? 'Rare' : 'Exotic';

        card.innerHTML = `
            ${controls}
            <h3>${recipe.name} <span style="font-size:12px; opacity:0.7;">(${rarityLabel})</span></h3>
            <div class="recipe-reqs">
                <strong>Requires:</strong> ${recipe.essences.join(', ')}<br><br>
                ${recipe.description}
            </div>
        `;

        card.onclick = () => {
            if (totalIngredientsAdded === 0) return;
            if (canAddMore) {
                selectedRecipes[recipe.id] = (selectedRecipes[recipe.id] || 0) + 1;
                updateRecipes();
            }
        };
        container.appendChild(card);
    });

    const brewBtn = document.getElementById('brewBtn');
    if (Object.keys(selectedRecipes).length > 0) {
        brewBtn.classList.add('active');
    } else {
        brewBtn.classList.remove('active');
    }

    if (container.innerHTML === '' && totalIngredientsAdded > 0) {
        container.innerHTML = '<div class="empty-recipes-msg">No suitable recipes for this mixture.</div>';
    }
}

function roll3d6() {
    return (Math.floor(Math.random() * 6) + 1) + (Math.floor(Math.random() * 6) + 1) + (Math.floor(Math.random() * 6) + 1);
}

function brewRecipe() {
    if (Object.keys(selectedRecipes).length === 0) return;

    for (let id in selectedRecipes) {
        let count = selectedRecipes[id];
        let recipe = recipesData.find(r => r.id == id);

        for (let i = 0; i < count; i++) {
            recipe.essences.forEach(rawE => {
                let p = parseEssence(rawE);
                let name = p.name;
                let type = p.type;

                if (essenceState[name][type] > 0) {
                    essenceState[name][type]--;
                    updateEssenceUIText(name);

                    let totalOfThis = essenceState[name].common + essenceState[name].rare + essenceState[name].exotic;
                    let maxIntensity = 4;
                    let currentIntensity = totalOfThis * 0.5;
                    let finalIntensity = Math.min(currentIntensity, maxIntensity);

                    let circleClass = '.e-' + name.toLowerCase();
                    let circleEl = document.querySelector(circleClass);
                    if (circleEl) {
                        circleEl.style.setProperty('--intensity', finalIntensity);
                    }
                }
            });
        }
    }

    let kettleWrap = document.querySelector('.kettle-wrap');
    kettleWrap.classList.add('crafted');
    setTimeout(() => kettleWrap.classList.remove('crafted'), 500);

    let roll = roll3d6();
    let diceEl = document.getElementById('diceResult');

    diceEl.classList.remove('big-dice');

    if (roll === 18) {
        diceEl.innerHTML = "<span>6</span> <span>6</span> <span>6</span>";
        diceEl.classList.add('big-dice');
        diceEl.style.setProperty('--glow-color', '#e64a4a');
        diceEl.style.setProperty('--glow-dark', '#4a1515');
    } else if (roll === 3) {
        diceEl.innerHTML = "<span>1</span> <span>1</span> <span>1</span>";
        diceEl.classList.add('big-dice');
        diceEl.style.setProperty('--glow-color', '#60c487');
        diceEl.style.setProperty('--glow-dark', '#2f4728');
    } else {
        diceEl.innerText = roll;
        if (roll >= 17) {
            diceEl.style.setProperty('--glow-color', '#e64a4a');
            diceEl.style.setProperty('--glow-dark', '#4a1515');
        } else if (roll <= 4) {
            diceEl.style.setProperty('--glow-color', '#60c487');
            diceEl.style.setProperty('--glow-dark', '#2f4728');
        } else {
            diceEl.style.setProperty('--glow-color', '#ffffff');
            diceEl.style.setProperty('--glow-dark', '#888888');
        }
    }

    diceEl.classList.remove('show');
    void diceEl.offsetWidth;
    diceEl.classList.add('show');

    // I save the history here
    let craftedRecipesList = [];
    for (let id in selectedRecipes) {
        let recipe = recipesData.find(r => r.id == id);
        craftedRecipesList.push({ name: recipe.name, count: selectedRecipes[id] });
    }

    if (craftedRecipesList.length > 0) {
        let now = new Date();
        craftingHistory.push({
            time: now.toLocaleTimeString('en-US', {hour: '2-digit', minute:'2-digit'}),
            recipes: craftedRecipesList,
            roll: roll
        });
    }

    let totalEssencesLeft = 0;
    baseEssencesList.forEach(name => {
        totalEssencesLeft += essenceState[name].common + essenceState[name].rare + essenceState[name].exotic;
    });

    if (totalEssencesLeft === 0) {
        resetAllAlchemy(); // I clear out the cauldron
    } else {
        selectedRecipes = {};
        updateRecipes();
    }
}

// My history modal logic
const historyBtn = document.getElementById('historyBtn');
const historyModalOverlay = document.getElementById('historyModalOverlay');
const closeHistoryBtn = document.getElementById('closeHistoryBtn');
const historyList = document.getElementById('historyList');

function renderHistory() {
    historyList.innerHTML = '';
    if (craftingHistory.length === 0) {
        historyList.innerHTML = '<div class="history-empty">You haven\'t brewed anything today yet.</div>';
        return;
    }

    // I display the latest brews at the top
    [...craftingHistory].reverse().forEach(entry => {
        let recipesHtml = entry.recipes.map(r => `<div><strong>${r.count}x</strong> ${r.name}</div>`).join('');

        let rollColor = '#ffffff';
        if (entry.roll >= 17) rollColor = '#e64a4a';
        else if (entry.roll <= 4) rollColor = '#60c487';

        historyList.innerHTML += `
            <div class="history-item">
                <div class="history-item-recipes">
                    <span style="font-size:12px; color: #b07a4a; margin-bottom: 4px;">${entry.time}</span>
                    ${recipesHtml}
                </div>
                <div class="history-roll" style="color: ${rollColor}; text-shadow: 0 0 10px ${rollColor};">
                    ${entry.roll}
                </div>
            </div>
        `;
    });
}

historyBtn.addEventListener('click', () => {
    renderHistory();
    historyModalOverlay.classList.add('active');
});

closeHistoryBtn.addEventListener('click', () => {
    historyModalOverlay.classList.remove('active');
});

historyModalOverlay.addEventListener('click', (e) => {
    if (e.target === historyModalOverlay) {
        historyModalOverlay.classList.remove('active');
    }
});

// I'm adding a scroll event listener to show/hide the history button
window.addEventListener('scroll', () => {
    const scrollableHeight = document.documentElement.scrollHeight - window.innerHeight;
    const scrollThreshold = scrollableHeight * (2 / 3);

    // If there is practically no scroll, I just show it, otherwise I check the 2/3 threshold
    if (scrollableHeight <= 0 || window.scrollY >= scrollThreshold) {
        historyBtn.classList.add('visible');
    } else {
        historyBtn.classList.remove('visible');
    }
});

// I also check the initial state on load in case the user refreshes midway down the page
window.dispatchEvent(new Event('scroll'));

// I disabled copying and right-clicking here
document.addEventListener('contextmenu', event => event.preventDefault());
document.addEventListener('dragstart', event => event.preventDefault());
document.addEventListener('selectstart', event => event.preventDefault());
document.addEventListener('copy', event => event.preventDefault());

window.addEventListener("load", () => {
    setupObserver();
    baseEssencesList.forEach(k => updateEssenceUIText(k));
    updateRecipes();
});
</script>

</body>
</html>
