$ = jQuery;
var page = 0;
var count_rows = -1;
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

        })
        .fail( function () { 

            page++;

            setTimeout(() => {
                setNextStep(page)
            }, 1000);
        });
    }

});