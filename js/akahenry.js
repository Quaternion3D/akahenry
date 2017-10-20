const projectList = {
    sudoku: {
        title: 'Sudoku Solver',
        description: '<strong>bla</strong> bla bla',
        media: '',
    },
    raspi: {
        title: 'Raspberry Pi Pet Feeder',
        description: 'It was a hassle for my family to come home in the middle of the day every day just to feed our dog, so I built an automatic pet feeding machine using Python and a Raspberry Pi. I also used a framework called NetIO to design a mobile app to control the feeder. Through the app, users can set a timer for when they want the feeder to dispense food.\
        <div class="aspect-ratio"><iframe src="https://player.vimeo.com/video/185593651?color=ffffff&title=0&portrait=0" frameborder="0" ></iframe></div>',
    },
    ballybally: {
        title: 'BallyBally RollyRolly',
        description: '\
        The goal is to roll the ball along 3D surfaces and around obstacles to the end of the puzzle. You can only control the orientation of the puzzle by changing the orientation of your hand (yaw, pitch, and roll to control rotation) and gravity does the rest.\
        <div class="aspect-ratio"><iframe src="https://player.vimeo.com/video/124155955?color=ffffff&title=0&portrait=0" frameborder="0"></iframe></div>',
    },
    henrythecoder: {
        title: 'HenryTheCoder',
        description: 'In 2011 I started a YouTube channel called HenryTheCoder, where I taught people how to program modifications (mods) into the game Minecraft. Minecraft is written in Java, and a large aspect of the game is its modding community. When I was first teaching myself how to make mods, I was dissatisfied at the quality of tutorials available online, so I decided to make my own tutorials. My goal was to create tutorials that were as clear and easy to follow as possible, so that\
         even people who had never programmed before could try them. I taught people how to "deobfuscate" the game\'s code so that it could be edited, modify it to add custom blocks, items, or abilities, and then repackage the modified code so that other players could install their newly created mod. To date, the channel has accumulated over 400,000 views and had over 4,300 subscribers at its peak. The difficulty with this project was that Minecraft is still in development, thus it is\
         constantly updating. Each update would often require my tutorials to be adjusted, which was very time consuming. I stopped updating the channel in 2013 in order to pursue other projects.\
         <img src="projects/HenryTheCoder.gif"><img src="projects/samplevideo.jpg" style="width:100%;">',
    }
}

// to allow fullscreen on vimeo videos: webkitallowfullscreen mozallowfullscreen allowfullscreen (in iframe)

const modal = $('#projectModal')
const animInClass = 'fadeIn'
const animOutClass = 'fadeOut'

modal.on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget)         // Button that triggered the modal
    var project = button.data('project')        // Extract info from data-* attributes
    // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
    // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
    modal.find('.modal-title').text(projectList[project].title)
    modal.find('.modal-description').html(projectList[project].description)
})  



/* CODE I GAVE UP ON
 * trying to add a fadeOut animation on modal exit
 * issue was adding the animOutClass on hide.bs.modal event doesn't work it will still hide the modal immediately.
 * so then you have to disable keyboard (esc), and use backdrop: 'static' to circumvent the hide.bs.modal event,
 * and then add your own listeners for esc and onClick modal (background), with your own closeModal function.
 * Which works, but THEN, since closeModal removes animInClass to add animOutClass,
 * you have to use your own openModal class which will add that class back in.
 * Anyway it just gets more complicated than it's worth right now. Seems like basically you end up overriding so much
 * what was the point of using a bootstrap modal anyway. Not worth the time right now!
*/

// test button to open modal
// const testButton = $('#test-button')
// testButton.on('click', openModal)

// function to openModal by adding animInClass and initializing the modal
// may need to add back the modal.on animationEnd so it opens after the animation
// function openModal(event) {
//     modal.addClass(animInClass);
//     modal.modal({
//         backdrop: 'static',
//         keyboard: false
//     });
// }

// close modal, remove animInClass, add animOutClass, and hide modal as soon as animOut animation ends
// function closeModal() {
//     modal.on('webkitAnimationEnd oanimationend msAnimationEnd animationend', function( event ) {
//       modal.modal('hide')
//     });
//     modal.removeClass(animInClass).addClass(animOutClass);
// }

// little thing for checking if modal is currently open, may not always be accurate
// function modalIsOpen(){
//     return modal.hasClass(animInClass)
// }
//

// trying to override default behavior on hide, doesn't work for some reason
// modal.on('hide.bs.modal', function (event) {
//     event.preventDefault()
//     modal.on('webkitAnimationEnd oanimationend msAnimationEnd animationend', function( event ) {
//       modal.modal('hide')
//       console.log('hide')
//     });
//     modal.removeClass(animInClass).addClass(animOutClass);
// })

// clean up classes and stuff after modal has disappeared
// modal.on('hidden.bs.modal', function (event) {
//     //       var closeModalBtns = modal.find('button[data-custom-dismiss="modal"]');
//     modal.off('webkitAnimationEnd oanimationend msAnimationEnd animationend')
//     modal.removeClass(animOutClass)
//     //       closeModalBtns.off('click')
// })

// close modal on click outside or esc
// modal.on('click', closeModal)
// document.addEventListener('keyup', function(event) {
//     if (event.keyCode == 27 && modalIsOpen()) {
//         closeModal()
//     }
// });
