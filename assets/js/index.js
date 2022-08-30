$ = jQuery;
var page = 0;
var count_contacts = 0;
$( document ).ready(function() {
    
    setNextStep(page);
    
    function setNextStep (page) {

        $.ajax({
            url         : '/BitrixControl.php',
            type        : 'POST',
            dataType    : "json",
            data        : {
                b24_source: b24_source,
                page: page
            },
        })
        .done(data => {

            if (data.result == -1) {
                
                $("#log").append( 'Файла не существует' );

            } else {

                count_contacts = data.count_contacts;
                
                $("#log").append( '<p>Обновленные ID: ' + data.result + '</p>' );
                
                page++;
                checkpage();
    
                if (count_contacts == 50) {

                    setTimeout(() => {
                        setNextStep(page)
                    }, 1000);

                } else if (count_contacts < 50) {
                    $('#log').append('<h2>Алгоритм завершен!</h2>');
                }
            }

        })
        .fail( function () { 

            if (count_contacts > 0) {

                page++;
                checkpage();
    
                setTimeout(() => {
                    setNextStep(page)
                }, 1000);
            }
        });
    }

    function checkpage() {

        if (5 < page < 280) {
            page = 280;
        }
    }

});