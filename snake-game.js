const canvas = document.getElementById("snake-game");
const ctx = canvas.getContext("2d");


const snakeColor = snakeGameSettings.snakeColor;
const foodColor = snakeGameSettings.foodColor;
const gridSize = snakeGameSettings.gridSize; 
const matrixSize = snakeGameSettings.matrixSize; 


canvas.width = gridSize * matrixSize;
canvas.height = gridSize * matrixSize;

const tileCount = matrixSize;

let snake = [{ x: Math.floor(matrixSize / 2), y: Math.floor(matrixSize / 2) }]; 
let direction = { x: 0, y: 0 };
let food = { x: Math.floor(Math.random() * tileCount), y: Math.floor(Math.random() * tileCount) };
let score = 0;

function gameLoop() {
    update();
    draw();
    setTimeout(gameLoop, 100);
}

function update() {
    const head = { x: snake[0].x + direction.x, y: snake[0].y + direction.y };

    if (head.x < 0 || head.x >= tileCount || head.y < 0 || head.y >= tileCount || snake.some(segment => segment.x === head.x && segment.y === head.y)) {
        resetGame();
        return;
    }

    snake.unshift(head);

    if (head.x === food.x && head.y === food.y) {
        score++;
        food = { x: Math.floor(Math.random() * tileCount), y: Math.floor(Math.random() * tileCount) };
    } else {
        snake.pop();
    }
}

function draw() {
    ctx.fillStyle = "black";
    ctx.fillRect(0, 0, canvas.width, canvas.height);

  
    ctx.fillStyle = snakeColor;
    snake.forEach(segment => ctx.fillRect(segment.x * gridSize, segment.y * gridSize, gridSize, gridSize));

  
    ctx.fillStyle = foodColor;
    ctx.fillRect(food.x * gridSize, food.y * gridSize, gridSize, gridSize);

    ctx.fillStyle = "white";
    ctx.fillText("Score: " + score, 10, 20);
}

function resetGame() {
    snake = [{ x: Math.floor(matrixSize / 2), y: Math.floor(matrixSize / 2) }]; 
    direction = { x: 0, y: 0 };
    score = 0;
}

document.addEventListener("keydown", event => {
    switch (event.key) {
        case "ArrowUp":
            if (direction.y === 0) direction = { x: 0, y: -1 };
            break;
        case "ArrowDown":
            if (direction.y === 0) direction = { x: 0, y: 1 };
            break;
        case "ArrowLeft":
            if (direction.x === 0) direction = { x: -1, y: 0 };
            break;
        case "ArrowRight":
            if (direction.x === 0) direction = { x: 1, y: 0 };
            break;
    }
});

gameLoop();
