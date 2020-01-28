
/**
 * CopyRights 2019 by FibooGroup
 *
 * @author Diego Favero
 *
 * Seller.js
 *
 *
 *
 *
 *
 */


MyAjaxGet = function (ur, dt, options) {

    if (!options) {

        options = {};
    }

    options.method = 'GET';

    return MyAjax(ur, dt, options);

}
MyAjaxPost = function (ur, dt, options) {

    if (!options) {

        options = {};
    }

    options.method = 'POST';

    return MyAjax(ur, dt, options);

}

MyAjax = function (ur, dt, options) {

    if (!options) {

        options = {};
    }



    options.data = dt;
    options.url = ur;


    if (options.dataType == 'json') {

        options.crossDomain = true


    }


    return $.ajax(options);
}


const __VPS_URL = '18.216.194.20/fibootkt/';
const __PROTOCOL = 'http';
const __LOCAL_URL = 'localhost/fibootkt/';
const __SOURCE = '/Ticket/pt/External/Sell';
const __ENV_FILE = 'MY_app_dev.php'


const VPS_RESOURCE = __PROTOCOL + '://' + __VPS_URL + 'Resources/public/';
const LOCAL_RESOURCE = __PROTOCOL + '://' + __LOCAL_URL + 'Resources/public/';

const __COOKIECHECKER = '/Cookie/ExternalCookieSetter';

//

var __parent;
var counterLoopLoad = 0;
var VPS_URL_SOURCE;
var LOCAL_URL_SOURCE;
var __CookieChecker;
var urlSource;
var CookieMainBundlePath;



/**
 * It will inject a few html codes on caller !
 */

class FibooSeller {

    constructor({locale = 'pt', evento, client, env, localhost, hoster = null} = {}) {

        if ( ! client ){

            throw 'Who Are You ?! \n Missing client  \n  FibooTickets Seller.js ';

        }
        if ( ! evento ){

            throw 'What Are you selling ?! \n Missing Event \n FibooTickets Seller.js ';

        }

        this.client = client;
        this.evento = evento;
        this.hoster = hoster ? hoster : null;



        this.locale = locale;

        urlSource = __PROTOCOL + '://';

        if (localhost) {

            urlSource += __LOCAL_URL;


        } else {

            urlSource += __VPS_URL;



        }

        CookieMainBundlePath = urlSource;

        if (!env || env == '' || env == 'prod') {

            this.env = 'prod';
            this.envLink = '';
            urlSource += __SOURCE;

            CookieMainBundlePath += __COOKIECHECKER;


        } else if (env == 'dev') {

            this.env = 'dev';
            this.envLink = __ENV_FILE;
            urlSource += __ENV_FILE + __SOURCE;
            CookieMainBundlePath += __ENV_FILE + __COOKIECHECKER;

        } else {

            throw 'Invalid Enviroment';

        }

        console.log(CookieMainBundlePath);
        this.urlSource = urlSource;


        this.TO = null;


        __parent = this;

        return this.Init();

    }


    JqueryLoader() {

        // Loop Breaker
        counterLoopLoad++;
        if (counterLoopLoad == 100) {
            throw 'I Need jQuery in order to do what I am suppose to do! \n   FibooTickets Seller.js ';
        }


        var __jquery = document.createElement('script');
        __jquery.src = "http://code.jquery.com/jquery-latest.min.js";
        __jquery.type = 'text/javascript';



        document.head.prepend(__jquery);


        var that = this;
        setTimeout(function () {
            that.Init();
        }, 500);


    }

    CheckCookies() {

        var uniq = new Date().getTime() + parseInt(Math.random() * 100000);
        console.log(uniq)
        console.log(CookieMainBundlePath + '/' + uniq);
        var __iframe = $('<iframe>');
        __iframe.attr('src', CookieMainBundlePath + '/' + uniq);
        __iframe.attr('data-unique', uniq)
        __iframe.attr('id', 'FibooTicketsCookieFisher');
        __iframe.css('width', 0)
        __iframe.css('height', 0)
        __iframe.appendTo('body');
    }

    Init() {


        if (typeof jQuery == 'undefined') {

            return this.JqueryLoader();
        } else {
            counterLoopLoad = 0;
        }

        this.CheckCookies();


        var that = this;



        var dataToPost = {
            'Evento': that.evento
            , 'env': that.env
            , 'client': that.client
            , 'locale': that.locale

        }

        jQuery.ajax({
            url: that.urlSource
            , data: dataToPost
            , method: 'POST'
            , success: function (data) {

                // var data = JSON.parse(resp);


                if (data.error) {

                    throw  data.message + '\n FibooTickets Seller.js ';

                }

                var DivHost = $('<div />')
                DivHost
                // .addClass('FibooTktDivHoster')
                    .html(data.view);

                var hoster;

                // if was defined the div to host contents
                if (__parent.hoster) {

                    hoster = $('#' + __parent.hoster);

                    // if hoster id was giving, but, the div doesnt exist, fall it to body
                    if (hoster.length == 0) {

                        hoster = $('body');

                    } else {

                        // clean the host
                        hoster.children().remove()

                            .promise()
                            .done(function () {


                                hoster.addClass('tk_pdng');

                                if (!__parent.hosterWidth) {

                                    hoster.addClass('tk_pdngWidth');
                                }

                                if (!__parent.hosterHeight) {

                                    hoster.addClass('tk_pdngHeight');
                                }


                            })


                    }
                    hoster.append(DivHost);


                } else {

                    return DivHost;
                }


                return true;


            }
        })

        ;



    }
}
