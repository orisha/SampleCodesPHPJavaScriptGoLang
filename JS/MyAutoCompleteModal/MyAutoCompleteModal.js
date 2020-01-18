/**
 * @author : Diego Favero
 *
 * @type {HTMLCollectionOf<Element>}
 * @private
 */




var __scripts= document.getElementsByTagName('script');
var __path= __scripts[__scripts.length-1].src.split('?')[0];      // remove any ?query
var mydir= __path.split('/').slice(0, -1).join('/')+'/';  // remove last filename part of path
var __isModalOpen = false;
var __ParentThis;
var __DivResultsScroll;
var __LastSearch = '';
var __InputBig
// will hold the modal object
var __DivHoodModal;
var __DivHoodView;

var __FirstLoad;


// holds the results
var __DivResults;

// modal pbjct size
var __DivWidth = 0;
var __DivHeight = 0;


var DivLoadingInfinite;

var AllLoaded = [];
var SearchLoopOnSearch;
var SearchLoopSearched;
var CountResultsPerSearch;
var KeepRollingTheSearchCountControl;
var AllPossibleSearch;
var HowManySearchSegments;
var HowManySearchSegmentsHasBeenProcessed;

(function ( $ ) {

        $.fn.MyAutoCompleteModal = function(opt) {

            // validations

            if ( !$(this).attr('id')){

                console.log(' Please, add ID attribute to Object')
                console.log(__this);
                return ;
            }

            AllLoaded.push($(this).attr('id'));


            DivLoadingInfinite = $('<div>').addClass('MYACM_KILL DivLoadingInfinite').attr('id', 'DivLoadingInfinite')


            var __this = $(this);



            if (!__ParentThis || __ParentThis.length <= 0){
                __ParentThis = $(this);
            }




            // asking for destroy, so do it !
            if (opt == 'destroy'){
                return __destroyIt();
            }





            // parameters default
            var options = $.extend({
                // These are the defaults.

                // length of typed before start
                minLength                       : 3

                // modal div class
                ,HoodClassModal                 : 'HoodClassModal'
                ,HoodView                       : 'HoodView'

                // input text class
                ,InputClassModal                : 'InputOnModalOuter Search'

                // this must handle the bg image, which will be replaced while loading
                ,InputClassModalBG              : 'Search'

                // wanna destroy all ? set me true
                ,destroyIt                      : false

                /**
                 * @param JqueryObject
                 * must be an Jquery Object
                 * each children must have the attr 'name' which must match on json result data set
                 * the template musta has css display none
                 */
                ,itemTemplate                   : ''

                //sets the css display for item template
                ,itemTemplateDisplay            : ''

                // delay for listen the input
                ,keyUpSensitivity               : 800

                // if true, Will Not Destroy it after click/select
                ,keepAliveAfterSelect           : false

                // img loading
                ,imgLoadingClass                : 'LoadingMyAutoCompleteModal'

                //empty result settings
                ,noResultMsg                    : ' Nenhum Resultado'
                ,noResultClass                  : 'NoResult'


                // for styling the 'X'
                ,closeButtonClassModal               : 'closeButtonClassModal'


                ,cleanInputOnDestroy            : true


                //Will Inject LIMIT on Mysql
                ,LimitResults             : undefined


                /**
                 *   @param array
                 *
                 *   It will Loop the search according to array values adding it on parameters
                 *   It will also ignore SearchLoop returned from server ans sticky to the array
                 */
                ,SearchLoopSequence             : undefined

                // if true, the search will continue forever, repeating the results after all been displayed
                // if != true, the search will stop after display AllPossibleSearch
                ,InfiniteLoopSearch             : true



            }, opt );

            // asking for destroy, so do it !
            if (options.destroyIt ){
                return __destroyIt();
            }


            if (!options || !options.source){

                console.log(' Need a URL on MyAutoCompleteModal')
                console.log(__this)

                return;
            }


            // events
            options = $.extend({

                beforeRender: $.noop
                ,allDone: $.noop
                ,onDestroy: $.noop
                ,onSelect: $.noop

            }, options);




            // functions

            /**
             * Destroys and close the the modal that hoods search results
             * @private
             */
            __destroyIt = function () {

                $(window).off('keyup');

                // don'' let it open again
                // clearTimeout(KeySensitivyTimer);

                clearTimeout($.data(this, 'dataPicker'));
                clearTimeout(__DivResultsScroll);

                __CleanMainUXSearchResults();

                // // remove the modal

                $("#" + __InputBig.attr('id')).val('').remove();

                $(".MainSearchTemplateResult").each(function () {

                    $(this).remove();
                });

                $(".MYACM_KILL").each(function () {

                    $(this).remove();
                });
                // set flag
                __isModalOpen = false;

                // callback
                options.onDestroy.call()

                // back to noraml
                $('body').removeClass('ToBody');


                if (options.cleanInputOnDestroy){

                    __ParentThis.val('');
                }

                __LastSearch = '';

                for (n in AllLoaded){

                    try{
                        if ( $("#" + AllLoaded[n]).hasClass('MYACM_KILL')){

                            $("#" + AllLoaded[n]).remove();
                        }
                    }
                    catch (e) {}
                }

                return;
            }

            /**
             * Checks if the css is already loaded, otherwise, loads it !
             * @param __href
             * @returns {boolean}
             * @private
             */

            __LoadCSS = function(__href) {


                var cssLink = $("<link rel='stylesheet' type='text/css' href='"+__href+"'>");

                if (!$("link[href='"+ __href +"']").length){

                    $("head").append(cssLink);

                }

                return true;

            };

            /**
             *  Creates and append to <body> the modal to hood search results
             * @private
             */
            __CreateModal = function(  ){


                // hide overflow
                $('body').addClass('ToBody');

                // create the modal border
                __DivHoodModal = $('<div>').appendTo('body').attr('id', '__DivHoodModal');
                __DivHoodModal.addClass('MYACM_KILL ' + options.HoodClassModal);


                __DivWidth = __DivHoodModal.width();
                __DivHeight = __DivHoodModal.height();

                // put a solid div over the modal border
                __DivHoodView = $('<div>').appendTo(__DivHoodModal).addClass('MYACM_KILL ' + options.HoodView).attr('id', '__HoodView__')


                // create an input text to hold search while modal is open

                // create an input text to hold search while modal is open
                var SerchResultCountHood = $('<div>')

                    .appendTo(__DivHoodView)
                    .addClass('MYACM_KILL SerchResultCountHood')
                    .attr('id', 'SerchResultCountHood')
                ;


                var SerchResultCountTxt = $('<div>')
                    .appendTo(SerchResultCountHood)
                    .addClass('MYACM_KILL Left SerchResultCount')

                    .html( ResultadosDaBuxcaTxr )


                ;
                var CloseBt = $('<div>')
                    .appendTo(SerchResultCountHood)
                    .html('X')
                    .addClass('MYACM_KILL ' + options.closeButtonClassModal)
                    .attr('alt', FecharALt +  '</br>[ESC]')
                    .on('click', function () {

                        __destroyIt();
                    });
                // var SerchResultCount = $('<span>')
                //     .appendTo(SerchResultCountTxt)
                //     .attr('id', 'SerchResultCount')
                //     .html( 'Total : ' )
                //
                // ;

                var __InputBigHood = $('<div>')
                    .appendTo(__DivHoodView)
                    .css('width', '90%')
                    .css('height', '10%')
                    .css('display', 'flex')
                    .css('margin', 'auto');



                __InputBig = $('<input>')

                    .appendTo(__InputBigHood)
                    .attr('type', 'text')
                    .val(__this.val() )
                    .addClass(' MYACM_KILL  InputOnModal')
                    .addClass(options.InputClassModal)
                    .addClass(options.InputClassModalBG)
                    .attr('id', __this.attr('id') + "__InputBig")
                    // .MyAutoCompleteModal(opt)
                    .on('keyup change', function () {

                        __this.val( $(this).val() );
                        __this.trigger('change');
                    })
                    .delay(20)
                    .focus();

                //
                // remove prev results
                $("#__Div__Results__").remove()

                //                    after remove the __DivResults, lets recreate it
                    .promise().done(function () {

                    __DivResults = $('<div>').addClass('MYACM_KILL  DivResults').appendTo(__DivHoodView);
                    __DivResults.attr('id', '__Div__Results__');


                    __DivResults.append(DivLoadingInfinite)



                    __DivResults.scroll(function (a, b, c) {

                        clearTimeout(__DivResultsScroll);

                        // console.log('IH ' + ($(this).scrollTop() + $(this).innerHeight()));
                        // console.log('SH '  + $(this)[0].scrollHeight );
                        // console.log( '----' );




                        if( $(this).scrollTop() + $(this).innerHeight() >= $(this)[0].scrollHeight - 500 ) {
                            __DivResultsScroll = setTimeout('InfiniteScroll()', 100 );
                        }
                    })

                    // apend it bottom to input


                });
            }


            InfiniteScroll = function(){

                __ExtendSearch();

            }

            __CleanMainUXSearchResults = function(){

                CountResultsPerSearch = 0;
                KeepRollingTheSearchCountControl = 0;
                HowManySearchSegmentsHasBeenProcessed = 0;
                HowManySearchSegments = 0;

                return $.ajax( { url: MainUXSearchCleanResults});
            }


            EmptyResult = function () {

                __CleanMainUXSearchResults();


                $('#__Div__Results__').children().each(function () {

                    $(this).remove()
                })
                    .promise()
                    .done(function () {

                        if ( __DivResults)
                            __DivResults.append(DivLoadingInfinite)


                    });

                return true;
            }


            __ExtendSearch = function(NoSearch){

                // $("#__Div__Results__").animate({ scrollTop: $('#__Div__Results__').prop("scrollHeight")}, 300);

                $("#DivLoadingInfinite").fadeIn();


                if (CountResultsPerSearch == AllPossibleSearch){

                    if (options.InfiniteLoopSearch == true){

                        __CleanMainUXSearchResults();
                    }
                    else {
                        $("#DivLoadingInfinite").fadeOut();
                        return true;

                    }

                }



                if ( !NoSearch){

                    var url_source = options.source + '?term=&LIMIT=21&id=' + __this.attr('id') ;
                    return DoTheAjaxSearch( url_source);
                }

            }

            /**
             * Creates the elements to display the results !
             * @param data
             * @private
             */
            __PlaceResult = function (data){

                    // boiling work
                    var Attr = function (who, __data) {

                        if ( __data){

                            return __data.attr(who);
                        }

                        return '';
                    }

                    var HasAttr = function (who, __data) {


                        if (__data.attr(who) != undefined) {

                            return true;
                        }
                        return false;

                    }


                    var count = 0;
                    // loop over the result
                    $(data).each(function () {

                        var __data = $(this);

                        var DivResultElement;

                        // if no templa†e defined, put on a table
                        if (!options.itemTemplate || options.itemTemplate.length == 0) {

                            // DivResultElement = $('<div>').appendTo(__DivResults).addClass('DivResultElement')
                            DivResultElement = $('<div>').insertBefore("#DivLoadingInfinite").addClass(' MYACM_KILL  DivResultElement')
                            DivResultElement.html(Attr('id', __data ) + " : " + Attr( 'label', __data));


                        } else {
                            // clone the template element
                            // DivResultElement = options.itemTemplate.clone().appendTo(__DivResults);
                            DivResultElement = options.itemTemplate.clone().insertBefore("#DivLoadingInfinite");

                            // prepare it
                            DivResultElement
                                .attr('id', DivResultElement.attr('id') + count)

                            if ( options.itemTemplateDisplay.length > 0){
                                DivResultElement
                                    .css('display', options.itemTemplateDisplay );

                            }

                            // loop o over elements ** elements should match with result data set attributes
                            DivResultElement
                                .children()
                                .each(function () {

                                    // if attr matchs, fill it with resulf
                                    if (HasAttr($(this).attr('name'), __data)) {

                                        $(this).html(Attr($(this).attr('name'), __data));
                                    }
                                    // no match : get rid of it
                                    else {
                                        $(this).remove();
                                    }

                                });

                        }

                        // start onSelect Listeners
                        DivResultElement.on('click', function () {

                            var toReturn = {
                                'parentObject' : __this
                                ,'data' : __data[0]

                            }
                            options.onSelect.call(toReturn);


                            if (!options.keepAliveAfterSelect) {
                                __destroyIt();
                            }


                        });
                        count ++;

                    })
                    // ajax is finished and processed, lets show the results
                    .promise().done(function () {


                            if (!__isModalOpen) {

                                // if it is opening the modal, let it be a littlefancy
                                $("#__DivHoodModal").height(0)
                                $("#__DivHoodModal").animate({
                                    height: '110%'

                                }, 'slow');
                            }

                        // let the script know the modal is open !
                        __isModalOpen = true;
                        // $("#DivLoadingInfinite").fadeOut();
                    });




                $(window).on('keyup', function (e) {

                    if (e.keyCode === 27) {
                        return  setTimeout('__destroyIt()',100);
                    }


                });
            }


            DoTheAjaxSearch = function(url_source, SearchLoop){

                if ( options.LimitResults ){

                    url_source += "&LIMIT=" + options.LimitResults
                }

                $.ajax({


                    url: url_source

                    // ajax success
                    ,success: function (resp) {

                        var data = JSON.parse(resp)


                        // event listener
                        options.beforeRender.call(data)

                        // result set not empty
                        CountResultsPerSearch += data.data.length;

                        AllPossibleSearch = data.AllPossibleSearch;


                        if ( data.data.length > 0) {
                            // __running = false;

                            __PlaceResult(data.data)
                        }
                        else {

                            if ( HowManySearchSegmentsHasBeenProcessed >= HowManySearchSegments  ) {
                                __ExtendSearch();
                            }
                        }

                    }

                })
                .promise()
                .done(function (data) {

                    // remove img loading
                    __this.removeClass(options.imgLoadingClass).addClass(  options.InputClassModalBG);

                    __isModalOpen = true;

                    if (!SearchLoop && data.SearchLoopSequence ){


                        var url_source = options.source + '?term=' + __this.val() + "&id=" + __this.attr('id') + "&SearchLoop=" + data.SearchLoopSequence;
                        __ExtendSearch(1);
                        DoTheAjaxSearch( url_source );

                    }

                    SearchLoopSearched = SearchLoop;
                    HowManySearchSegmentsHasBeenProcessed ++;

                    options.allDone.call();

                });
            }

            var KeepRollingTheSearchTC;


            /**
             * // So, It will check if all the search loop has been processed, then, will check if there is
             * enough results been displayed, if not, will trigger new search
             * @constructor
             */

            KeepRollingTheSearch = function(){


                if ( KeepRollingTheSearchCountControl < 20 ){
                    if ( HowManySearchSegmentsHasBeenProcessed < HowManySearchSegments  ){

                        KeepRollingTheSearchTC = setTimeout( 'KeepRollingTheSearch()', 200)
                        KeepRollingTheSearchCountControl ++;
                        return;
                    }
                }

                clearTimeout(KeepRollingTheSearchTC);

                if ( CountResultsPerSearch < 12){

                    __ExtendSearch();
                }


            }

            GetTheResults = function(){

                return $(function () {


                    if ( __this.val() != '' ){

                        StatsSaver('Search',  __this.val());
                    }
                    // if is set, it will loop the search includind its elements on params list
                    if ( options.SearchLoopSequence ){


                        HowManySearchSegments = options.SearchLoopSequence.length;

                        for (n in options.SearchLoopSequence){

                            var url_source = options.source + '?term=' + __this.val() + "&id=" + __this.attr('id') + "&SearchLoop=" + options.SearchLoopSequence[n];
                            __ExtendSearch(1)
                            SearchLoopOnSearch = options.SearchLoopSequence[n];
                            DoTheAjaxSearch( url_source, options.SearchLoopSequence[n] );

                        }

                    }
                    else {

                        // Do The normal search, but,
                        // it is still possible to  loop it including
                        // the SearchLoopSequence param on data result set

                        HowManySearchSegments = 1;
                        var url_source = options.source + '?term=' + __this.val() + "&id=" + __this.attr('id') ;
                        DoTheAjaxSearch( url_source);
                    }
                })

            }

            TriggerAjaxSearch = function(e){

                // ESC ? Destroy it
                if (e.keyCode === 27) {
                    return  setTimeout('__destroyIt()',100);
                }

                // destroy prev listened key
                clearTimeout($.data(this, 'dataPicker'));


                // Setting the timeout for the listener avoid the plugin to fire on every key pressed
                // to change the default delay, set options.keyUpSensitivity

                $.data(this, 'dataPicker', setTimeout(function() {


                    // if input type is empty, remove the results

                    if ( __this.val().length == 0 && options.minLength != 0){

                        EmptyResult();
                        // $('#SerchResultCount').html('??');
                        return;
                    }

                    // check min input length and if it is not same than previous search
                    if ( __this.val().length >= options.minLength && __LastSearch != __this.val()){


                        $(function () {

                            __LastSearch = __this.val();

                            // loading img on inout field
                            __this.removeClass(options.InputClassModalBG).addClass(  options.imgLoadingClass)


                            __LoadCSS(mydir + 'css/MyAutoCompleteModal.css');

                            // noModalOpen means it must create the modal
                            if (__isModalOpen !== true) {

                                __CreateModal(  );
                            }
                            else {

                                EmptyResult();

                            }
                        }).promise().done(function () {


                            GetTheResults()
                                .promise().done(function () {


                                setTimeout('KeepRollingTheSearch()', 500);

                            })
                        })

                    }
                    // }, 800));
                }, options.keyUpSensitivity));
            }

            if ( !__FirstLoad ){

                __CleanMainUXSearchResults();
                __FirstLoad = true;
            }

            // input type MAIN LIST∑N∑R listener
            __this.on('keyup change', function (e) {

                TriggerAjaxSearch(e)

            });

            return this;
    };

}( jQuery ));
