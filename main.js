// Задать сдвиг мемов по странице (в пикселях)
let shift = 55;

// Добавить обработку клика на главный мем
document.getElementById('main').onclick = function () {
    alert('Это главный мем!');
};

// Получить список всех второстепенных мемов
let memes = document.getElementsByClassName('meme');

// Получить случайное число
function getRandom(max) {
    return Math.floor(Math.random() * max);
}

// Раскидать мемы по странице
for (let i = memes.length - 1; i >= 0; i--) {
    memes[i].style.left = getRandom(document.documentElement.clientWidth - 55) + 'px';
    memes[i].style.top = getRandom(document.documentElement.clientHeight - 55) + 'px';
}

// Поставить таймер, чтоб мемы прыгали
function interval(timeout) {
    return setInterval(() => {
        for (let i = memes.length - 1; i >= 0; i--) {
            let currentX = parseInt(memes[i].style.left) || 0;
            let currentY = parseInt(memes[i].style.top) || 0;

            let newDirectionX = getRandom(3) - 1;
            let newDirectionY = getRandom(3) - 1;

            let newX = currentX + newDirectionX * shift;
            let newY = currentY + newDirectionY * shift;

            if (newX >= 0 && newX + 55 <= document.documentElement.clientWidth) {
                memes[i].style.left = newX + 'px';
            }
            
            if (newY >= 0 && newY + 55 <= document.documentElement.clientHeight) {
                memes[i].style.top = newY + 'px';
            }
        }
    }, timeout);
}

let jumper = interval(1000);

// Добавить обработку клика на каждый второстепенный мем
for (let i = memes.length - 1; i >= 0; i--) {
    memes[i].onclick = function () {
        // Получить и сохранить данные главного мема
        let main = document.getElementById('main');
        let mainSrc = main.src;
        let mainAlt = main.alt;

        // Сделать главным новый мем
        main.src = this.src;
        main.alt = this.alt;

        // Сделать бывший главный мем второстепенным
        this.src = mainSrc;
        this.alt = mainAlt;

        // Увеличить счёт
        let score = parseInt(document.getElementById('score').innerText) || 0;
        document.getElementById('score').innerText = score + 1;

        if (score == 4) {
            alert('Ты перешёл на следующий уровень!');
            clearInterval(jumper);
            jumper = interval(750);
            document.getElementById('score_title').style.background = '#FF00C7';
        }

        if (score == 9) {
            alert('Ты жжёшь!');
            document.getElementById('score_title').style.background = '#0511FF';
            clearInterval(jumper);
            jumper = interval(500);
            shift = 110;
        }

        if (score == 14) {
            alert('Хардкор!');
            document.getElementById('score_title').style.background = '#FF0019';
            document.getElementById('score').style.fontSize = '76px';
            clearInterval(jumper);
            jumper = interval(250);
            shift = 165;
            setInterval(() => {
                if (document.getElementById('score_title').getAttribute('data-color') == 'red') {
                    document.getElementById('score_title').style.background = '#fff';
                    document.getElementById('score_title').style.color = '#000';
                    document.getElementById('score_title').setAttribute('data-color', 'white');
                } else {
                    document.getElementById('score_title').style.background = '#FF0019';
                    document.getElementById('score_title').style.color = '#fff';
                    document.getElementById('score_title').setAttribute('data-color', 'red');
                }
            }, 100);
        }
    };
}