


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
    
    
// contest modal
document.getElementById('contest-btn').addEventListener('click',
    function () {
        document.querySelector('.contest-modal').style.display = 'flex';
    });

document.querySelector('.close-contest').addEventListener('click',
    function () {
        document.querySelector('.contest-modal').style.display = "none";
    });            


document.getElementById('add-file').addEventListener('click',
    function() {
        document.getElementById('team_photo').click();
    });

document.getElementById('team_photo').addEventListener("change",
    function(){
        if (document.getElementById('team_photo').value) {
            document.getElementById('custom-text').innerHTML = document.getElementById('team_photo').value.match(/[\/\\]([\w\d\s\.\-\(\)]+)$/)[1];
        } else {
            document.getElementById('custom-text').innerHTML = "No file chosen, yet";
        }
    });




document.getElementById('add-player-file').addEventListener('click',
    function () {
        document.getElementById('player_profilPic').click();
    });

document.getElementById('player_profilPic').addEventListener("change",
    function () {
        if (document.getElementById('player_profilPic').value) {
            document.getElementById('custom-player-text').innerHTML = document.getElementById('player_profilPic').value.match(/[\/\\]([\w\d\s\.\-\(\)]+)$/)[1];
        } else {
            document.getElementById('custom-player-text').innerHTML = "No file chosen, yet";
        }
    });   




document.getElementById('add-game-file').addEventListener('click',
    function () {
        document.getElementById('game_photo').click();
    });

document.getElementById('game_photo').addEventListener("change",
    function () {
        if (document.getElementById('game_photo').value) {
            document.getElementById('custom-game-text').innerHTML = document.getElementById('game_photo').value.match(/[\/\\]([\w\d\s\.\-\(\)]+)$/)[1];
        } else {
            document.getElementById('custom-game-text').innerHTML = "No file chosen, yet";
        }
    });    
    




 $(document).ready(function () {
     $(".table").tablesorter();
 })






