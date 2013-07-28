<?php
namespace app\components;

use yii\base\Component;
use yii;
use app\models\User;
use \app\models\Conversation;
use \app\models\Message;

class Fixtures extends Component
{
    private $faker;
    
    function __construct() {
        $this->faker = \Faker\Factory::create();
    }
    
    /**
     * Generates single user
     */
    public function generateUser() {
        $fakeUser = new User;

        $fakeUser->email      = $this->faker->email;
        $fakeUser->password   = '123';
        $fakeUser->first_name = $this->faker->firstName;
        $fakeUser->last_name  = $this->faker->lastName;
        $fakeUser->save();
    }

    /**
     * Generates users
     * @param integer $number number of users to generate
     */
    public function generateUsers($number) {
        for( $i = 0; $i < $number; $i++) {
            $this->generateUser();
        }
    }
        
     /**
     * Generates comversation
     */
    public function generateConversation() {  
        $fakeConversation = new Conversation;
        
        $fakeConversation->title = $this->faker->word . 'Conversation';
        $fakeConversation->save(); 
        
        $idArr = [];
        for($i = 0; $i < 2; $i++) {
            /*Generate number of fake user*/
            $allUsers = User::find()
                    ->all();
            $numFakeUser = rand(0, count($allUsers)-1);
            /*----------------------------*/
        
            $userToSubscribe = User::find()
                    ->where(array('id' => $allUsers[$numFakeUser]->id))
                    ->one();
            $idArr[$i] = $userToSubscribe->id;
        }
        
        $fakeConversation->addSubscribed($idArr);
    }

    /**
     * Generates conversations
     * @param integer $number number of conversations to generate
     */
    public function generateConversations($number) {
        for( $i = 0; $i < $number; $i++) {
            $this->generateConversation();
        }
    }
    
    /**
     * Generates message
     */
    public function generateMessage() {  
        $fakeMessage = new Message;
        $fakeConversation = new Conversation;
        
        /*Generate number of fake conversation*/
        $allConversations = Conversation::find()->all();
        $numFakeConversation = rand(0, count($allConversations)-1);
        /*----------------------------*/
        
        $fakeMessage->conversation_id = $allConversations[$numFakeConversation]->id;
        $fakeConversation->id = $fakeMessage->conversation_id;
             
        /*Generate number of fake user, whos is participant in conversation.*/
        $allUsers = User::find()
                    ->all();
        $numFakeUser = 0;
        $idFakeUser = 0;
        for(;;) {
            $numFakeUser = rand(0, count($allUsers)-1);
            
            $idFakeUser = $allUsers[$numFakeUser]->id;
            $boo = $fakeConversation->isConversationMember($idFakeUser);
            if($boo) {
                break;
            }
        }
        /*----------------------------*/
        
        $fakeMessage->user_id = $idFakeUser;
        
        $fakeMessage->body = $this->faker->text;
        $fakeMessage->save();
    }
    
    /**
     * Generates messages
     * @param integer $number number of messages to generate
     */
    public function generateMessages($number) {
        for( $i = 0; $i < $number; $i++) {
            $this->generateMessage();
        }
    }
}
