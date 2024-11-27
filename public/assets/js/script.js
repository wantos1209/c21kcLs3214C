// epxpand side-nav
$(document).ready(function () {
    var initialLogoSrc = $('.gmb_logo').attr('src');
    var initialContainerClass = $('.sec_container_utama').attr('class');
    var isExpanded = false;
    $(document).on('click', '#icon_expand', function () {
        isExpanded = !isExpanded;
        if (isExpanded) {
            $('.gmb_logo').attr('src', function (index, oldSrc) {
                return oldSrc.replace('lucky-wheel-l21.png', 'icon-spinner.png');
            });
            $('.sec_container_utama').addClass('noexpand');
            $('.data_sidejsx').removeClass('active');
            $('.sub_data_sidejsx').css('display', 'none');
        } else {
            $('.gmb_logo').attr('src', initialLogoSrc);
            $('.sec_container_utama').attr('class', initialContainerClass);
            $('.sub_data_sidejsx').css('display', '');
        }
    });
    $(document).on('mouseenter', '.sec_sidebar', function () {
        if (isExpanded) {
            $('.gmb_logo').attr('src', function (index, oldSrc) {
                return oldSrc.replace('icon-spinner.png', 'lucky-wheel-l21.png');
            });
            $('.sec_container_utama').removeClass('noexpand');
        }
    });
    $(document).on('mouseleave', '.sec_sidebar', function () {
        if (isExpanded) {
            $('.gmb_logo').attr('src', function (index, oldSrc) {
                return oldSrc.replace('lucky-wheel-l21.png', 'icon-spinner.png');
            });
            $('.sec_container_utama').addClass('noexpand');
        }
    });
    $(document).on('click', '.data_sidejsx, .sub_data_sidejsx', function () {
        $('.gmb_logo').attr('src', initialLogoSrc);
        $('.sec_container_utama').attr('class', initialContainerClass);
        isExpanded = false;
    });
});

// copy komponent
document.addEventListener('click', function (event) {
    var target = event.target;
    if (target.classList.contains('copy_element')) {
        var elementId = target.previousElementSibling.firstElementChild.id;
        copyElement(elementId);
    }
});
function copyElement(elementId) {
    var element = document.getElementById(elementId);
    var range = document.createRange();
    range.selectNode(element);
    window.getSelection().removeAllRanges();
    window.getSelection().addRange(range);
    document.execCommand('copy');
    window.getSelection().removeAllRanges();
    Swal.fire({
        icon: 'success',
        title: 'Element berhasil disalin!',
        showConfirmButton: false,
        timer: 1500
    });
}

// crud
$(document).ready(function () {
    $(document).on('click', '.dot_action', function () {
        var actionCrud = $(this).next('.action_crud');
        $('.action_crud').not(actionCrud).slideUp('fast');
        if (actionCrud.is(':hidden')) {
            actionCrud.slideDown('fast');
        } else {
            actionCrud.slideUp('fast');
        }
    });
    $(document).on('click', function (event) {
        if (!$(event.target).closest('.dot_action, .action_crud').length) {
            $('.action_crud').slideUp('fast');
        }
    });
});

// img show tabel
$(document).ready(function () {
    $(document).on('mouseenter', '.td_img_show', function () {
        $(this).next('.table_img').css('display', 'block');
    });
    $(document).on('mouseleave', '.td_img_show', function () {
        $(this).next('.table_img').css('display', 'none');
    });
});

// show modal
$(document).ready(function () {
    $(document).on('click', '.triggermodal', function () {
        var target = $(this).data('target');
        $('#' + target).css('display', 'flex');
    });
    $(document).on('click', '.closemodal', function () {
        $(this).closest('.sec_modal').css('display', '');
    });
    $(document).on('click', function (event) {
        var target = $(event.target);
        if (!target.closest('.componen_modal').length && !target.closest('.triggermodal').length) {
            $('.sec_modal').css('display', 'none');
        }
    });
});

// search table
$(document).ready(function () {
    $('body').on('keyup', 'input[id^="searchData-"]', function () {
        var searchValue = $(this).val().toLowerCase().trim();
        var targetClass = $(this).attr('id').split('-')[1];
        $('tr.filter_row').nextAll('tr').each(function () {
            var text = $(this).find('td').find('.' + targetClass).text().toLowerCase().trim();
            if (text.includes(searchValue)) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    });
});


function salinTeks(teks) {
    getDataUrl(function (url) {
        teks = url.url + teks;
        const el = document.createElement('textarea');
        el.value = teks;
        document.body.appendChild(el);
        el.select();
        document.execCommand('copy');
        document.body.removeChild(el);

        Swal.fire({
            icon: 'success',
            title: 'Berhasil disalin!',
            showConfirmButton: false,
            timer: 1500
        });
    });
}

function getDataUrl(callback) {
    $.ajax({
        url: '/getDataUrl',
        type: 'GET',
        success: function (data) {
            callback(data);
        },
        error: function () {
            callback('');
        }
    });
}

// full screen
$(document).ready(function () {
    var isFullscreen = false;

    $('.fullscreen').click(function () {
        if (!isFullscreen) {
            $('.title_main_content, .sec_top_navbar, .sec_sidebar, .footer').css('display', 'none');

            $('.content_body').css('max-height', '100vh');
            $('.tabelproses').css('margin-bottom', '5vh');

            $('.fullscreen svg').html('<svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24"><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 3v3a2 2 0 0 1-2 2H3m18 0h-3a2 2 0 0 1-2-2V3M3 16h3a2 2 0 0 1 2 2v3m8 0v-3a2 2 0 0 1 2-2h3" /></svg>');

            isFullscreen = true;
        } else {
            $('.title_main_content, .sec_top_navbar, .sec_sidebar, .footer').css('display', '');

            $('.content_body').css('max-height', '');
            $('.tabelproses').css('margin-bottom', '');

            $('.fullscreen svg').html('<svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 16 16"><path fill="currentColor" d="m5.3 6.7l1.4-1.4l-3-3L5 1H1v4l1.3-1.3zm1.4 4L5.3 9.3l-3 3L1 11v4h4l-1.3-1.3zm4-1.4l-1.4 1.4l3 3L11 15h4v-4l-1.3 1.3zM11 1l1.3 1.3l-3 3l1.4 1.4l3-3L15 5V1z" /></svg>');

            isFullscreen = false;
        }
    });
});

// Time dashboard
function updateDateTime() {
    var currentDate = new Date();
    var day = currentDate.getDate();
    var month = currentDate.toLocaleString('default', { month: 'long' });
    var year = currentDate.getFullYear();

    var hours = currentDate.getHours();
    var minutes = currentDate.getMinutes();
    var seconds = currentDate.getSeconds();

    hours = (hours < 10 ? "0" : "") + hours;
    minutes = (minutes < 10 ? "0" : "") + minutes;
    seconds = (seconds < 10 ? "0" : "") + seconds;

    document.getElementById("root_breadtime").textContent = day + " " + month + " " + year + " | " + hours + ":" + minutes + ":" + seconds + " WIB";
}

// histori coin
$(document).ready(function () {
    $(".toggleshowsidedata").click(function () {
        $(".groupdataside").toggleClass("expanded");
        $(".sec_container_utama").toggleClass("noexpand");
    });
});


// show modal
$(document).ready(function () {
    $('.showmodal').click(function () {
        var target = $(this).data('modal');
        $('.modalhistory[data-target="' + target + '"]').css('display', 'flex');
    });
    $('.closetrigger').click(function () {
        $('.modalhistory').css('display', 'none');
    });
    $(document).mouseup(function (e) {
        var container = $(".secmodalhistory");
        if (!container.is(e.target) && container.has(e.target).length === 0) {
            $('.modalhistory').css('display', 'none');
        }
    });
});

// display none notifikasi data h2
$(document).ready(function () {
    checkCountPendingData();

    function checkCountPendingData() {
        $('.countpendingdata, .countdatapend').each(function () {
            // var countValue = $(this).text().trim();
            // if (countValue === '' || countValue === '0') {
            //     $(this).css('display', 'none');
            // } else {
            //     $(this).css('display', '');
            // }
        });
    }

    $('.countpendingdata').bind('DOMSubtreeModified', function () {
        checkCountPendingData();
    });
});

// show logout
$(document).ready(function () {
    $('.sec_top_navbar').load('/topnav');
    $(document).on('click', '.profile_nav', function () {
        $('.list_menu_profile').slideToggle('fast');
    });
    $(document).on('click', function (event) {
        if (!$(event.target).closest('.list_menu_profile, .profile_nav').length) {
            $('.list_menu_profile').slideUp('fast');
        }
    });
});

// cari menu
$('#sec_sidebar').on('input', '#searchTabel', function () {
    var searchText = $(this).val().toLowerCase();
    $('.nav_title1, .sub_title1').each(function () {
        var titleText = $(this).text().toLowerCase();
        var $parentData = $(this).closest('.data_sidejsx');
        var $parentSubData = $(this).closest('.sub_data_sidejsx');

        if (searchText === '') {
            $(this).show();
            $parentData.show();
            $parentSubData.hide();
            $parentData.removeClass('active');
            $parentSubData.removeClass('active');
        } else if (titleText.includes(searchText) || $parentSubData.find('.sub_title1').text().toLowerCase().includes(searchText)) {
            $(this).show();
            $parentData.show();
            $parentSubData.show();
            $parentData.addClass('active');
            $parentSubData.addClass('active');
        } else {
            $(this).hide();
            $parentData.hide();
            $parentSubData.hide();
            $parentData.removeClass('active');
            $parentSubData.removeClass('active');
        }
    });
});

//Notifikasi Sound
function playSound(url) {
    const audio = new Audio(url);
    audio.play().catch(function (error) {
        console.log('Audio playback failed:', error);
    });
}

$(document).ready(function () {
    function checkNotifications() {
        $.ajax({
            url: '/getNotifikasi',
            method: 'GET',
            dataType: 'json',
            success: function (response) {
                if (response.countDP > 0) {
                    $('#countDP').text(response.countDP);
                }

                if (response.countWD > 0) {
                    $('#countWD').text(response.countWD);
                }

                if (response.dataDepo && response.dataDepo.length > 0) {
                    response.dataDepo.forEach(function (item) {
                        updateIsnotif(item.id);
                    });
                }
            },
            error: function (xhr, status, error) {
                console.log('Error:', error);
            }
        });
    }

    function updateIsnotif(id) {
        $.ajax({
            url: '/updateNotifikasi/' + id,
            method: 'GET',
            dataType: 'json',
            success: function (response) {
                playSound('/assets/notification/notif.wav');
            },
            error: function (xhr, status, error) {
                console.log('Error:', error);
            }
        });
    }


    /* ----P1N---- */
        function shuffleArray(array) {
            for (let i = array.length - 1; i > 0; i--) {
                const j = Math.floor(Math.random() * (i + 1));
                [array[i], array[j]] = [array[j], array[i]];
            }
            return array;
        }
        var numbers = [0, 1, 2, 3, 4, 5, 6, 7, 8, 9];
        numbers = shuffleArray(numbers);

        var buttons = $('.numpad button:not(.delete)').toArray();

        buttons.forEach(function(button, index) {
            var number = numbers[index];
            $(button).data('value', number);
            $(button).text(number);
        });

        /* numpad pin click */
        $('.numpad-btn').click(function() {
            var number = $(this).text();
            var currentPin = $('.pin-input').filter(function() {
                return this.value === '';
            }).first();
            if (currentPin.length > 0) {
                currentPin.val(number);
            }
        });

        $('.delete').click(function() {
            $('.pin-input').filter(function() {
                return this.value !== '';
            }).last().val('');
        });
    /* -------- */

    setInterval(checkNotifications, 5000);

    updateDateTime();
    setInterval(updateDateTime, 1000);
});