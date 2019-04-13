// team modal

document.getElementById('team-btn').addEventListener('click',
function() {
    document.querySelector('.team-modal').style.display = 'flex';
});

document.querySelector('.close-team').addEventListener('click',
    function(){
       document.querySelector('.team-modal').style.display = "none"; 
    });



// player modal
document.getElementById('player-btn').addEventListener('click',
    function () {
        document.querySelector('.player-modal').style.display = 'flex';
    });

document.querySelector('.close-player').addEventListener('click',
    function () {
        document.querySelector('.player-modal').style.display = "none";
    });    



// game modal
document.getElementById('game-btn').addEventListener('click',
    function () {
        document.querySelector('.game-modal').style.display = 'flex';
    });

document.querySelector('.close-game').addEventListener('click',
    function () {
        document.querySelector('.game-modal').style.display = "none";
    });    
    
    
// game modal
document.getElementById('contest-btn').addEventListener('click',
    function () {
        document.querySelector('.contest-modal').style.display = 'flex';
    });

document.querySelector('.close-contest').addEventListener('click',
    function () {
        document.querySelector('.contest-modal').style.display = "none";
    });            