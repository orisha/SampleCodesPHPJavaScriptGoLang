<?php
/**
 **
 * Since the system is using and array identified by client for seasson
 *
 * this service will prototype the Synfony's Session, and then, use it !
 *
 * On Action, the session will be $sess[CLIENT], but, the system will take it only as $sess
 *
 *
 *
 * This class has been used with dynamic and on the fly  database connection, so it has many dependencies not include on this sample package
 *
 *
 *
 * @author Diego Favero
 * #|@since 2015/04/28
 */

namespace MyService\MySessionUI;



use FIBOO\FibooBundle\Entity\BaseDeDados;
use FIBOO\FibooBundle\Entity\BaseAtiva;


use Symfony\Component\HttpFoundation\Request;
use BUYER\BuyerBundle\Entity\Login;
use BUYER\BuyerBundle\Entity\Usuario;


use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


class MySessionUI {

    var $client;

    /**
     * @var BaseDeDados
     */
    var $Client;

    /**
     * @var \stdClass
     */
    var $MySessionUI;


    var $MySessionUILifeTime;

    /**
     * @var \Doctrine\ORM\EntityManager
     */
    protected $Dynamic;

    /**
     * @var \Doctrine\ORM\EntityManager
     */
    protected $Eventos;

    /**
     * @var \Doctrine\ORM\EntityManager
     */
    protected $Buyer;

    /**
     * @var \Doctrine\ORM\EntityManager
     */
    protected $Stats = null;

    /**
     * @var Container
     */
    protected $container;

    /**
     * @var Session
     */
    protected $session;

    /**
     * @param Session $session
     * @param Container $container
     * @throws \Exception
     */
    public function __construct( Session $session, Container $container ){


        $this->container = $container;
        $this->session = $session;

        if (!$session->has('MySessionID')){

            $this->SetSessionID();
        }


        if (!$this->has('MySessionUILifeTime')) {

            $this->MySessionUILifeTime = $this->container->getParameter('MySessionUILifeTime');
            $this->session->set('MySessionUILifeTime', $this->MySessionUILifeTime);
            $this->set('MySessionUILifeTime', $this->MySessionUILifeTime);
        }


        /**
         * If session life time has been expired, just log the user out instead to block the page as necessary on BackEnd
         */
        if (is_numeric($this->MySessionUILifeTime) AND $this->MySessionUILifeTime > 0){

            if (
                $session->getMetadataBag()->getLastUsed()
                AND (
                    time() - $session->getMetadataBag()->getLastUsed() > $this->MySessionUILifeTime
                )
            ){

                if ($this->has('userUI')){

                    $this->remove( 'userUI' );
                }

                return true;

            }
        }

        $this->setClient();

        return $this;

    }

    /**
     * Sets A random string to be used as Session ID
     * @param null $val
     * @return bool
     * @throws \Exception
     */
    public function SetSessionID($val = null)
    {


        if (!$val) {

            $val = uniqid() . rand(666, 666666);
        }

        $this->session->set('MySessionID', $val);
        $this->set('MySessionID', $val);

        return true;
    }

    /**
     *
     * if value == null, parameter must be array or object
     *
     * @param $parameter
     * @param $value
     * @return bool
     * @throws \Exception
     */
    public function set($parameter, $value = null, $noRegisterStats = null)
    {


        if (!$this->MySessionUI){

            $this->setClient();

        }

        if ($value){

            $this->MySessionUI->{$parameter} = $value;
        }
        else{
            if (is_object($parameter) OR is_array($parameter)){

                foreach($parameter as $k => $v ){

                    $this->MySessionUI->{$k} = $v;
                }
            }
            else{
                // null value
                $this->MySessionUI->{$parameter} = $value;
            }
        }



        $this->session->set($this->client, $this->MySessionUI);


        if (!$noRegisterStats and $this->has('cookieUI') and $this->get('cookieUI')) {


            return $this->StatsSession();


        }

        return true;

    }

    /**
     * Register on database infos about cookies and session for gather stats data and to be possible to restore the session
     *
     * @param null $id
     * @return bool
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function StatsSession($id = null)
    {


        if (!$id) {
            $id = $this->get('cookieUI');
        }


        if (!$this->Stats) {

            $this->Stats = $this->container->get('doctrine')->getManager('Stats');

        }

        $this->Stats->beginTransaction();
        $SessionStats = $this->Stats->getRepository('CookieBundle:Session')->findOneByCookie($id);

        if (!method_exists($SessionStats, 'getId')) {

            $SessionStats = new \STATS\CookieBundle\Entity\Session();
            $SessionStats->setSessionID($this->GetSessionID());

        }


        $SessionStats->setSess($this->MySessionUI);
        $SessionStats->setUpdated(new \DateTime());

        $this->Stats->persist($SessionStats);
        $this->Stats->flush($SessionStats);
        $this->Stats->commit();

        return true;


    }


    /**
     *
     * Uses the stored data to restore the session
     *
     * @param $id
     * @return bool
     * @throws \Exception
     */
    public function RestoreStatsSession($id)
    {


        if (!$this->Stats) {

            $this->Stats = $this->container->get('doctrine')->getManager('Stats');

        }

        $SessionStats = $this->Stats->getRepository('CookieBundle:Session')->findOneByCookie($id);

        if (!method_exists($SessionStats, 'getId')) {
            return true;
        }

        $StatsSess = $SessionStats->getSess();

        $this->MySessionUI = new \stdClass();

        foreach ($StatsSess as $k => $j) {

            $this->set($k, $j, true);
        }


        $this->session->set('MySessionID', $SessionStats->getSessionID());
        $this->MySessionUI->MySessionID = $SessionStats->getSessionID();



        if ($this->has('Cart') and count($this->get('Cart'))) {

            $Cart = array();
            foreach ($this->get('Cart') as $cart) {

                $Cart[$cart->getId()] = $cart;
            }

            $this->set('Cart', $Cart, 1);
        }


        return true;


    }

    /**
     * @param $id
     * @return bool
     */
    public function SetCookie($id)
    {

        return $this->setCookieID($id);


    }

    /**
     *
     * This class has been used with dynamic and on the fly  database connection
     *
     * @param BaseDeDados|null $Client
     * @return bool
     * @throws \Exception
     */
    public function setClient(BaseDeDados $Client = null){

        if (!$Client){

            $Client = $this->container->get('get.client')->GetClient();


        }

        $this->Client = $Client ;
        $this->client = $Client->getPrefixo().'UI'
        ;
        $this->session->set('clientUI', $this->client, 1);


        if ($this->session->has($this->client)){

            $this->MySessionUI = $this->session->get($this->client);

        }
        else{
            $this->MySessionUI = new \StdClass();
        }



        $this->set('Conn', $this->container->get('get.client')->GetConn());
        return true;
    }

    /**
     * @param $parameter
     * @return bool|null
     * @throws \Exception
     */
    public function has($parameter){

        if (!$this->MySessionUI){

            $this->setClient();

        }

        if ( isset($this->MySessionUI->{$parameter}) and !is_null($this->MySessionUI->{$parameter})   ){
            return true;
        }

        return null;
    }

    /**
     * @return int
     */
    public function GetSessionID(){

        return  $this->session->get('MySessionID');

    }

    /**
     * MultiTabHandler
     */
    public function ResetTabs(){


        $this->MySessionUI->tabCounter = 0;
        $this->MySessionUI->Tab = array();
        $this->MySessionUI->subTab = array();

    }

    /**
     * @return array
     * @throws \Exception
     */
    public function GetAllParameters(){

        if (!$this->MySessionUI){

            $this->setClient();

        }
        $ret = array();
        foreach($this->MySessionUI as $k => $v){
            $ret[] = $k;

        }


        return $ret;
    }

    /**
     * @return array
     * @throws \Exception
     */
    public function GetAllValues()
    {

        if (!$this->MySessionUI) {

            $this->setClient();

        }
        $ret = array();
        foreach ($this->MySessionUI as $k => $v) {
            $ret[$k] = $v;

        }


        return $ret;
    }

    /**
     * @param $parameter
     * @return bool
     * @throws \Exception
     */
    public function remove($parameter, $noRegisterStats = null)
    {

        if (!$this->MySessionUI){

            $this->setClient();

        }

        if ($this->has($parameter)){
            unset($this->MySessionUI->{$parameter});
        }

        if (!$noRegisterStats and $this->has('cookieUI') and $this->get('cookieUI')) {


            return $this->StatsSession();


        }

        return true;
    }

    /**
     * @param null $message
     * @return bool
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function KillSession($message = null){

        $this->session->remove( $this->client );

        if ($message)
            throw new NotFoundHttpException($message, null, 423);


        return true;
    }

    /**
     *
     * This is what register the session when a user logs in
     *
     * @param Usuario $userUI
     * @param null $UI
     * @return bool
     * @throws \Exception
     */
    public function AuthRegisterSession(Usuario $userUI, $UI = null){


        $this->Dynamic = $Dynamic = $this->container->get('doctrine')->getManager('Dynamic');
        $this->Buyer = $Buyer = $this->container->get('doctrine')->getManager('Buyer');


        if (method_exists($userUI, 'getId')){


            // register session

            $request = Request::createFromGlobals();
            $locale = $request->getLocale();



            if (in_array($this->container->get('kernel')->getEnvironment(), array('dev'))){

                $_enviroment = 'dev';
            }
            elseif (in_array($this->container->get('kernel')->getEnvironment(), array('test'))){

                $_enviroment = 'dev';
            }
            else{

                $_enviroment = 'prod';
            }

            $LastLogin = $Buyer->getRepository('BuyerBundle:Login')->MyFindByUsuario($userUI);
            $LastLogin = end($LastLogin);


            $Login = new Login();
            $Login->addUsuario($userUI);

            // set last login
            $Login->setLogin();
            $Login->setSessionId( $this->GetSessionID() );
            $Login->setClient( $this->Client->getId() );

            $Buyer->persist($Login);



            $Buyer->flush();

            $Main = $Buyer->getRepository('BuyerBundle:Pessoa')->find(

                $this->container->getParameter('pessoa_principal')
            );


            $MainDadosPessoais = $Buyer->getRepository('BuyerBundle:DadosPessoais')->findOneByPessoa(

                $Main
            );


            $Logado = $userUI->getPessoa();



            $DadosPessoais = $Buyer->getRepository('BuyerBundle:DadosPessoais')->findOneByPessoa($Logado);
            $nome = trim($DadosPessoais->getNome().' '.$DadosPessoais->getSobrenome());


            $Email = $Buyer->getRepository('BuyerBundle:PessoaEmail')->findOneBy(
                array(
                    'pessoa'  => $Logado
                ,'principal' => 1
                )
            )->getEmail();

            $Telefone = $Buyer->getRepository('BuyerBundle:PessoaTelefone')->findOneBy(
                array(
                    'pessoa'  => $Logado
                ,'principal' => 1
                )
            );



            $Common = $this->container->get('doctrine')->getManager('Common');
            $CommonPais = $Common->getRepository('CommonBundle:Pais');



            $ddi = $CommonPais->find($Telefone->getPais())->getDdi();


            $telefone = $Telefone->getDdd().$Telefone->getNumero();


            $Passaporte = $Buyer->getRepository('BuyerBundle:Passaporte')->findOneByPessoa($Logado);




            if (method_exists($Passaporte, 'getId')){

                $Documento = $Passaporte->getPassaporte();

                $DocType = 'Passaporte';
                $Pais = $Passaporte->getPais();
                $PaisLabel = $CommonPais->find($Passaporte->getPais())->getNome();

            }
            else{


                $Doc = null;
                $Doc = $Buyer->getRepository('BuyerBundle:PessoaFisica')->findOneByPessoa($Logado);


                if (is_null($Doc ) or (!method_exists($Doc, 'getId'))){

                    $Doc = $Buyer->getRepository('BuyerBundle:Passaporte')->findOneByPessoa($Logado);

                    $Documento = $Doc->getPassaporte();
                    $DocType = 'Passaport';
                    $Pais = $Doc->getPais();
                    $PaisLabel = $Common->getRepository('CommonBundle:Pais')->find($Pais)->getNome();
                }
                else{


                    $Documento = $Doc->getCpf();
                    $DocType = 'CPF';
                    $Pais = '1';
                    $PaisLabel = 'Brasil';
                }


            }


            /**
             * The session data to be shared across the system
             */
            $sess[$this->client] = array(

                'userUI'                        => $userUI
            ,'LastLogin'                    => $LastLogin
            ,'_locale'                      => $locale
            ,'_enviroment'                  => $_enviroment
            ,'DadosPessoais'                => $DadosPessoais
            ,'CheckOutData'                 => array(

                    'Email'                      => $Email
                ,$DocType                    => $Documento
                ,'Pais'                      => $Pais
                ,'PaisLabel'                 => $PaisLabel
                ,'Telefone'                  => $telefone
                ,'TelefonePais'              => $Telefone->getPais()
                ,'TelefonePaisLabel'         => $CommonPais->find($Telefone->getPais())->getNome()
                ,'Nome'                      => $nome
                ,'DataNascimentoRaw'         => $DadosPessoais->getDataNascimento()
                ,'DataNascimentoPT'          => $DadosPessoais->getDataNascimento()->format('d/m/Y')
                ,'DataNascimento'            => $DadosPessoais->getDataNascimento()
                ,'Ddi'                       => $ddi
                ,'Gender'                    => $DadosPessoais->getGender()
                ,'Language'                  => $DadosPessoais->getLanguage()
                ,'userUI'                    => $userUI->getId()

                )

//                ,'Labels'                       => $Labels
            ,'Client'                       => $this->Client
            ,'client'                       => $this->client
            ,'LoginEntry'                   => $Login->getId()
//                ,'MultiTab'                     => $Tab->getMultiTab()
            ,'Main'                         => $Main
            ,'MainDadosPessoais'            => $MainDadosPessoais
            ,'SessionID'                    => $this->GetSessionID()
//                ,'cookieUI'                       => $this->GetCookieID()
            );


            $Client = $this->container->get('get.client')->GetCLient();


            if ($this->has('Cart')){

                if (count($this->get("Cart")) > 0 ){

                    $this->SetCartOwner($Logado->getId());
                }


            }


            return $this->register($sess, $Client);

        }

    }

    /**
     * @param null $parameter
     * @return \stdClass|null
     * @throws \Exception
     */
    public function get($parameter = null){

        if (!$this->MySessionUI){

            $this->setClient();
        }

        try{

            if ($parameter){
                return $this->MySessionUI->{$parameter};
            }
            else{
                return $this->MySessionUI;
            }
        }
        catch( \Exception $e ){
            return null;
        }

    }

    /**
     * @param $pessoa
     * @return bool
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function SetCartOwner($pessoa){


        $CartOwed = array();

        $this->Eventos = $this->container->get('doctrine')->getManager('Eventos');

        foreach (
            $this->get("Cart")
            as
            $Cart
        ){



            if ($Cart->getSessionID() == $this->GetSessionID()){



                $Cart->setPessoa($pessoa);
                $this->Eventos->persist($Cart);
                $this->Eventos->flush();

            }

            $CartOwed[] = $Cart;
        }

        $this->set('Cart', $CartOwed, 1);

        return true;

    }


    /**
     * @param array $sess
     * @param BaseDeDados $Client
     * @return bool
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     * @throws \Exception
     */
    public function register(array $sess, BaseDeDados $Client = null){

        if (!$Client)
            $Client = $this->container->get('get.client')->GetCLient();


        $this->setClient($Client);

        $Session = new \StdClass();


        if (is_array($sess[$this->client])){

            $arr = $sess[$this->client];
            $sessionID = $this->registerOnDB($sess[$this->client]['userUI'] ? $sess[$this->client]['userUI'] :null );
            $Session->BaseAtiva = $this->MySessionUI->BaseAtiva = $sessionID;

        }
        else
            $arr = $sess;


        foreach($arr as $k => $v ){


            $Session->{$k} = $this->MySessionUI->{$k} = $v;
        }


        $this->session->set($this->client, $Session);


        if ($this->MySessionUI->cookieUI) {


            $this->SetCookie($this->MySessionUI->cookieUI);
            $this->StatsSession();


        }

        return true;
    }

    /**
     * @param Usuario $userUI
     * @return int
     * @throws \Exception
     */
    private function registerOnDB( Usuario $userUI = null){


        if (!$userUI){

            $userUI = $this->has('userUI')  ? $this->get('userUI')  : null;

        }

        //return $this->container->get('MySessionUIDBConnected')->registerOnDB();
        $Fiboo = $this->container->get('doctrine')->getManager('Fiboo');


        $Active = new BaseAtiva();
        $Active->setBaseDeDados( $this->container->get('get.client')->GetCLient()->getId() );
        $Active->setUsuario(  $userUI->getId() );

        $Fiboo->beginTransaction();

        $Fiboo->persist($Active);
        $Fiboo->flush();
        $Fiboo->commit();

        return $Active->getId();

    }

    /**
     * zero or null for infinite session]
     * @return bool
     * @throws \Exception
     */
    public function SetLifeTime($MySessionUILifeTime){


        $this->session->set('MySessionUILifeTime', $MySessionUILifeTime);
        $this->set('MySessionUILifeTime', $MySessionUILifeTime);
        $this->MySessionUILifeTime = $MySessionUILifeTime;

        return true;
    }

    /**
     *
     * zero or null for infinite session
     * @return integer
     *
     *
     */
    public function GetLifeTime(){

        return $this->MySessionUILifeTime;
    }

    /**
     * @return bool
     * @throws \Exception
     */
    public function isLogged(){

        if (!$this->has('userUI')){

            echo json_encode(

                array(
                    'error' => true
                ,'message' => $this->container->get('translator')->trans(
                    'Por Favor, Log In'
                )
                )
            );
            die;
        }

        return true;

    }

    /**
     * @param $val
     * @return bool
     * @throws \Exception
     */
    public function setCookieID($val){

        $this->set('cookieUI', $val, 1);
        $this->set('CookieID', $val, 1);
        return true;
    }

    /**
     * @return \stdClass|null
     * @throws \Exception
     */
    public function GetCookieID(){

        return $this->get('CookieID');
    }

    /**
     * @param $__request
     * @return bool
     * @throws \Exception
     */
    public function SessionRestorer($__request)
    {

        if ($__request->has('Cookie')) {

            $this->setCookieID($__request->get('Cookie'));
            $this->RestoreStatsSession($__request->get('Cookie'));
        }

        $this->container->get('response.json')->CheckIfItIsExternal(1);

        return true;
    }

}
