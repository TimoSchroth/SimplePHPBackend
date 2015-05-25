<?php
class dao_voting extends _auth{
    
    public function vote(){
        $request = $this->get_request();
        
        $data = "";
        $mood = $this->mysqli->real_escape_string($request['mood']);

        $total_votes = $this->get_total_votes_today();
        if($total_votes['success']){
            $dateToday = new DateTime();
            
            $vote_count = $total_votes['total_votes'] + 1;
            
            $query_insert_vote = "INSERT INTO user_moods (user_id, mood, date, mood_type)
                                  VALUES ('".$_SESSION['user_id']."', '".$mood."', '".$dateToday->format('Y-m-d H:i:s')."', '".$vote_count."')";

            if ($this->mysqli->query($query_insert_vote) !== TRUE) {
                $data = "Voting konnte nicht erstellt werden. (".$this->mysqli->error.")"; 
            } else {
                $data = $vote_count;
            }
        } else {
            $data = $total_votes;
        }
   
        return $data;
    }
    
    public function get_total_votes_today(){
        $data['success'] = true;
        $data['msg'] = '';

        $dateToday = new DateTime();
        $dateTomorrow = new DateTime();
        $dateTomorrow->modify('+1 day');

        $query_total_votes = "SELECT * FROM user_moods "
                           . "WHERE user_id = '".$_SESSION['user_id']."' "
                           . "AND date >= '".$dateToday->format('Y-m-d')."' "
                           . "AND date < '".$dateTomorrow->format('Y-m-d'). "'";
        

        if($result_total_votes = $this->mysqli->query($query_total_votes)){
            $data['total_votes'] = mysqli_num_rows($result_total_votes); 
        } else {
            $data['false'] = true;
            $data['msg'] = $this->mysqli->error;               
        }
   
        return $data;        
    }    
 
    private function add_credits($credits){
        $data = array();
        $data['success'] = true;
        $data['credits'] = 0;
        
        $query_update_credit = "UPDATE users "
                             . "SET credits=credits+".$credits." "
                             . "WHERE user_id = '".$_SESSION['user_id']."'";

        if($result_update_credit = $this->mysqli->query($query_update_credit)){
                $row = mysqli_fetch_array($result_update_credit); 
                $data['credits'] = $row['credits'];
        } else {
            $data['success'] = false;               
        }
   
        return $data;
    }    
    
    private function add_quote($quote_id){
        $data = array();
        $data['success'] = true;
        
        $dateToday = new DateTime('NOW');
        
        $query_insert_quote = "INSERT INTO user_quotes (user_id, quote_id, date)
                               VALUES ('".$_SESSION['user_id']."', '".$quote_id."', '".$dateToday->format('Y-m-d H:i:s')."')";
        
        if($this->mysqli->query($query_insert_quote)  !== TRUE){
            $data['success'] = false;
        }
        
        return $data;        
    }    

    public function get_random_quote(){

        $data = array();
        $data['success'] = true;
        $data['msg'] = '';

        $query_random_quote = "SELECT qu.quote as qt, qu.quote_author as qa, qu.quote_id as qi FROM quotes qu "
                            . "WHERE qu.quote_id NOT IN ("
                            . "SELECT quote_id FROM user_quotes "
                            . "WHERE user_id = '".$_SESSION['user_id']."') "
                            . "ORDER BY RAND() LIMIT 0,1";      

        if($result_random_quote = $this->mysqli->query($query_random_quote)){
                $row = mysqli_fetch_array($result_random_quote); 
                $this->mysqli->autocommit(FALSE);
                $add_quote = $this->add_quote($row['qi']);
                $add_credits = $this->add_credits(1);
                if($add_quote['success'] && $add_credits['success']){
                    $this->mysqli->commit();
                    $data['quote_text'] =  htmlentities($row['qt']);
                    $data['quote_author'] = htmlentities($row['qa']);
                    $data['credits'] = $add_credits['credits'];
                } else {
                    $this->mysqli->rollback();
                    $data['success'] = false;
                    $data['msg'] = $this->mysqli->error;                    
                }
                
        } else {
            $data['success'] = false;
            $data['msg'] = $this->mysqli->error;
        }
   
        return $data; 
    }     
    
    private function add_smiley($smiley_id){
        $data = array();
        $data['success'] = true;
        
        $dateToday = new DateTime('NOW');
        
        $query_insert_smiley = "INSERT INTO user_smilies (user_id, smiley_image_id, date)
                               VALUES ('".$_SESSION['user_id']."', '".$smiley_id."', '".$dateToday->format('Y-m-d H:m:s')."')";
        
        if($this->mysqli->query($query_insert_smiley)  !== TRUE){
            $data['success'] = false;
        }
        
        return $data;         
    }
    
    public function get_random_smiley(){

        $data = array();
        $data['success'] = true;
        $data['msg'] = '';
        
        $query_random_smiley = "SELECT si.url as url, si.smiley_image_id as image_id FROM smiley_images si "
                             . "WHERE si.smiley_image_id NOT IN ("
                             . "SELECT smiley_image_id FROM user_smilies "
                             . "WHERE user_id = '".$_SESSION['user_id']."') "
                             . "ORDER BY RAND() LIMIT 0,1";      

        if($result_random_smiley = $this->mysqli->query($query_random_smiley)){
            $row = mysqli_fetch_array($result_random_smiley); 
            $this->mysqli->autocommit(FALSE);
            $add_smiley = $this->add_smiley($row['image_id']);
            $add_credits = $this->add_credits(1);
            if($add_smiley['success'] && $add_credits['success']){
                $this->mysqli->commit();
                $data['url'] = $row['url'];
                $data['credits'] = $add_credits['credits'];
            } else {
                $this->mysqli->rollback();
                $data['success'] = false;
                $data['msg'] = $this->mysqli->error;                    
            }
        } else {
            $data['success'] = false;
            $data['msg'] = $this->mysqli->error;              
        }
   
        return $data; 
    }     
    
}
