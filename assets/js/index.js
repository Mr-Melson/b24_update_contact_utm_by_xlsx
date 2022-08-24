$ = jQuery;
var page = 0;
var count_rows = 0;
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

            if (data.count_rows == -1) {
                
                $("#log").append( 'Файла не существует' );
            } else {

                count_rows = data.count_rows;
    
                jQuery.map( data.result, function( item, i ) {
                    $("#log").append( 'ID: ' + i + '. Имя контакта: ' + item + ' - метки установлены' );
                });
                
                page++;
    
                if (count_rows/50 >= page){
                    setTimeout(() => {
                        setNextStep(page)
                    }, 1000);
                } else{
                    $('.details').append('<h2>Алгоритм завершен!</h2>');
                }
            }


        })
        .fail( function () { 

            if (count_rows > 0) {
                page++;
    
                setTimeout(() => {
                    setNextStep(page)
                }, 1000);
            }
        });
    }

});