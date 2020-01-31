

/**
 * dec 2019
 * @author Diego Favero
 */



/**

 sample use

 var task = AsyncNFCAPI(this)

 val req =
 NFCAPIRequest (
 BaseUrl = "http://192.169.9.168:9663/",
 action = "SellerStatement",
 payload = mapOf("seller" to 1, "first" to 0, "last" to 5),
 method = "POST"

 )

 GlobalScope.launch( context = Dispatchers.Main){

    task?.execute(req)
}

 GlobalScope.launch( context = Dispatchers.Main){

    println( "Here gets the result from async task " +  task?.get().toString() )
}


 */

package com.example.loginbytag

import android.annotation.SuppressLint
import android.app.Activity
import android.os.AsyncTask
import khttp.responses.Response
import org.json.JSONObject
import java.lang.Exception

@SuppressLint("StaticFieldLeak")
class AsyncNFCAPI(private var activity: Activity?) : AsyncTask&#60NFCAPIRequest, Int, JSONObject?>() {

    override fun onPreExecute() {
        super.onPreExecute()
//        activity?.progressBar?.visibility = View.VISIBLE

        return
    }

    override fun doInBackground(vararg req: NFCAPIRequest?): JSONObject? {

        if ( !mySession.isInit()){
        return null
    }
    KillByInactivity().SetToKill()


    var req = if ( req[0] != null ) req[0] else return null

    var  response : Response


    if ( req?.method == "POST"){

        response  = khttp.post(
            req?.BaseUrl  + req?.action
            ,json= req?.payload
        )
    }
    else   if ( req?.method == "GET"){
        response  = khttp.get(
            req?.BaseUrl  + req?.action
            ,json= req?.payload
        )
    }
    else {
        return null
    }

    try{

        return response?.jsonObject
    }

    catch(e: Exception){

        println(  "Error : " + response.text )
        return null

    }



}

    override fun onProgressUpdate(vararg values: Int?) {
        super.onProgressUpdate(*values)
        return
    }


    override fun onPostExecute(result: JSONObject?) {
        super.onPostExecute(result)
        return
    }


}
