simulate = {};

window.addEventListener("load", () => {
    simulate.bindEvents();
});

simulate.bindEvents = () =>
{
    const eleGetData = document.querySelector('#get_data');
    eleGetData.addEventListener('click', (event) => {
        event.preventDefault();
        simulate.fetchData();
    });
}


simulate.fetchData = async function () {

    simulate.renderGif();

    try {

        const elePxData = document.querySelector('#px_data').value.toLowerCase();

        const response = await fetch(
            `https://pokeapi.co/api/v2/pokemon/${elePxData}`,
            {
                method: 'GET'
            }
        );

        if (!response.ok) {
            throw new Error("Could not fetch resource.");
        }

        const data = await response.json();
        const imgSprite = data.sprites.front_default;

        eleSprite = document.querySelector('#img_sprite');
        eleSprite.src = imgSprite;
        eleSprite.style.display = "block";

        console.log(img);

    }
    catch (error) {
        console.error(error);
    }

    simulate.unrenderGif();

}

simulate.renderGif = () =>
{
    console.log('renderGif');
}

simulate.unrenderGif = () =>
{
    console.log('unrendgerGif');
}

