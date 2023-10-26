let food_array = [];

let belch = new Audio('sounds/belch.mp3');
let eat = new Audio('sounds/eat.mp3');

function getCookie(name) {
    const value = `; ${document.cookie}`;
    const parts = value.split(`; ${name}=`);
    if (parts.length === 2) return parts.pop().split(';').shift();
}

let put = (food) => {
    food_array.push(food);
    console.log('');
    console.log(food);
    console.log('');
};

let create_fish = () => {
    let my_fish;

    fetch('https://meme.mrbaco.ru/api/fishes/index.php').then(r => r.json()).then(r => {
        for (let i = 0; i < r.length; i++) {
            
            if (r[i].id == fish) {
                my_fish = r[i];
                break;
            }
        }

        if (my_fish != undefined) {
            let fish = document.createElement("div");

            fish.className = 'fish';
            fish.style = 'width: 80px;';
            fish.id = 'fish_' + my_fish.id;
            fish.innerHTML = '<div class="level"><div class="filled" style="width: 0;"></div></div><img src="https://meme.mrbaco.ru/002/aquarium/fishes/' + my_fish.image + '.png" />';

            document.getElementsByTagName('body')[0].appendChild(fish);
        }
    });
};

let feed = () => {
    if (fish) {
        fetch('https://meme.mrbaco.ru/api/fishes/index.php', {
            method: 'PUT',
            body: JSON.stringify({
                id: fish,
                food: food_array
            })
        }).then(r => {
            if (r.status == 200) {
                eat.play();
                create_fish();
            } else if (r.status != 204) belch.play();
        });
    }
};

document.addEventListener('DOMContentLoaded', () => {
    if (document.getElementById('qwe')) {
        put('qwe');
    
        let qwe_element = document.getElementById('qwe');
    
        if (qwe_element.style.width == '300px' &&
            qwe_element.style.height == '300px' &&
            qwe_element.style.backgroundColor != undefined) {
                put('qwe_sized');
        }
    }
    
    if (document.getElementById('button')) {
        put('button');
    }
    
    if (document.getElementById('food')) {
        put('food');
    }
    
    if (document.getElementById('rty')) {
        put('rty');
    
        let rty_element = document.getElementById('rty');
    
        if (rty_element.style.width == '10px' &&
            rty_element.style.height == '10px' &&
            rty_element.style.backgroundColor != undefined) {
                put('rty_sized');
        }
    }

    create_fish();
});