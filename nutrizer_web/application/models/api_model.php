<?php 

defined('BASEPATH') OR exit('No direct script access allowed');
                        
class Api_model extends CI_Model {

    public function insertOTP($username,$code)
    {
        $data = array('username'=>$username,'code'=>$code);
        $this->db->insert('tbl_otp', $data);
        return $this->db->affected_rows() > 0;
    }

    public function isOTPMatch($username, $otp)
    {
        $this->db->select('count(1) total_found');
        $this->db->from('tbl_otp');
        $this->db->where('code', $otp);
        $this->db->where('username', $username);
        $this->db->where('TIMESTAMPDIFF(MINUTE,idt,NOW()) < 60');
        $query = $this->db->get();
        $result = $query->row_array();
        if (isset($result['total_found'])) {
            return $result['total_found'] > 0;
        } else {
            return false;
        }
    }

    public function checkUsername($username)
    {
        $this->db->select('id,username,isdeleted,islogin,lastloginfrom,islocked,lastlogin,lastlogin_id');
        $this->db->from('tbl_user');
        $this->db->where('username', $username);
        $query = $this->db->get();
        $result = $query->row_array();
        return $result;
    }


    public function getUser($username)
    {
        $this->db->select("id,username,name,'user' as privilege, isdeleted,islogin,lastloginfrom,islocked,lastlogin,lastlogin_id,subscription_id");
        $this->db->from('tbl_user');
        $this->db->where('username', $username);
        $this->db->where('isdeleted', 0);
        $query = $this->db->get();
        $result = $query->row_array();
        return $result;
    }


     public function getUserProfile($username)
    {
        $this->db->select("id,username,name,'user' as privilege, profile,birth_date,gender,email,avatar_img,subscription_id,is_accept_term");
        $this->db->from('tbl_user');
        $this->db->where('username', $username);
        $this->db->where('isdeleted', 0);
        $query = $this->db->get();
        $result = $query->row_array();
        return $result;
    }


    public function createUser($data)
    {
        $this->db->insert('tbl_user', $data);
        $insert_id = $this->db->insert_id();
        return  $insert_id;
    }

    public function updateUser($username, $data)
    {
        $this->db->where('username', $username);
        $this->db->update('tbl_user', $data);
        return $this->db->affected_rows() > 0;
    }

     public function insertLoginHistory($data)
    {
        $this->db->insert('tbl_login', $data);
        return $this->db->affected_rows() > 0;
    }


     public function getSubscriptionById($id)
    {
        $this->db->select("username,id,otp,plan_type, (SELECT sub.plan_title FROM tbl_subscribe_plan sub WHERE sub.plan_type=a.plan_type) plan_type_name,
            status_subs,start_time,end_time,payment_required,payment_paid,payment_via,payment_userid,payment_recurring,DATEDIFF(end_time,NOW()) lifetime_left");
        $this->db->from('tbl_subscribe a');
        $this->db->where('id', $id);
        $this->db->where('isdeleted', 0);
        $query = $this->db->get();
        $result = $query->row_array();
        return $result;
    }


      public function getUserSubscription($username)
    {
        $this->db->select("username,id,otp,plan_type, (SELECT sub.plan_title FROM tbl_subscribe_plan sub WHERE sub.plan_type=a.plan_type) plan_type_name,
            status_subs,start_time,end_time,payment_required,payment_paid,payment_via,payment_userid,payment_recurring,DATEDIFF(end_time,NOW()) lifetime_left");
        $this->db->from('tbl_subscribe a');
        $this->db->where("id = IFNULL((SELECT sub.subscription_id FROM tbl_user sub WHERE sub.username='".$username."'),-1)");
        $this->db->where('isdeleted', 0);
        $query = $this->db->get();
        $result = $query->row_array();
        return $result;
    }


    public function getConfig($id)
    {
        $this->db->select("value,idt,udt");
        $this->db->from("tbl_config");
        $this->db->where("option", $id);
        $this->db->where("enable", "TRUE");
        $query = $this->db->get();
        return $query->row_array();
    }


    public function getGroupTagInfo($idList=array())
    {
        $this->db->select("id,
                title,
                type, 
                total_member total");
        $this->db->from("tbl_group_tag a");
        $this->db->where("isdeleted", 0);
        $this->db->where_in($idList);
        $this->db->order_by("RAND()");
        $query = $this->db->get();
        return $query->result_array();
    }

// (CASE WHEN b.count_play > 999999 THEN CONCAT(FORMAT(b.count_play/1000000,1),'M') WHEN b.count_play > 999 THEN CONCAT(FORMAT(b.count_play/1000,1),'K') ELSE b.count_play END) countLabel, IF(DATEDIFF(b.idt, CURDATE()) < 14,'NEW','') tagLabel
    public function getGroupTagMemberAlbum($idGroup,$streamUrlImage,$offset=0,$limit=6)
    {
        $sql = "SELECT 'album' type,
            a.id_member id,CONCAT('".$streamUrlImage."',b.image) imageUrl,b.album_name title, 'http://musica.tl' linkShare,
            (
            SELECT sub.full_name
            FROM tbl_artist sub
            WHERE sub.id = (
            SELECT subx.artist_id
            FROM tbl_album_artist subx
            WHERE subx.album_id=b.id LIMIT 1)) subtitle,
            IFNULL((SELECT SUM(sub.count_play) FROM tbl_song sub WHERE sub.album_id=b.id),0) countLabel,
            IF(DATEDIFF(b.idt, CURDATE()) < 14,'NEW','') tagLabel
            FROM tbl_group_tag_member a
            INNER JOIN tbl_album b ON a.id_member = b.id
            WHERE a.id_group_tag='".$idGroup."'  LIMIT ".$offset.",".$limit;
        
        $query = $this->db->query($sql);
        return $query->result_array();

    }


    function getSongsByQuery($query,$username,$streamUrlImage,$streamUrlAudio,$streamUrlLyric,$offset=0,$limit=6){
        $sql="SELECT
            a.id,a.title,a.artist_id artistId,(SELECT sub.full_name FROM tbl_artist sub WHERE sub.id=a.artist_id) artistName,
            a.album_id albumId,(SELECT sub.album_name FROM tbl_album sub WHERE sub.id=a.album_id) albumName,
            ROUND(a.duration*1000,0) duration,
             count_like likesCount, 
            count_play playCount,
            CONCAT('".$streamUrlImage."',(SELECT sub.image FROM tbl_album sub WHERE sub.id=a.album_id)) imageUrl,
            CONCAT('".$streamUrlAudio."',a.file) streamUrl,
            CONCAT('".$streamUrlLyric."',a.file_lyric) lyricUrl,
            IF(song_type='PREMIUM',TRUE,FALSE) isPremium,
            IF(a.file_instrument IS NULL OR a.file_lyric IS NULL,0,1) canSing,
            'http://musica.tl' linkShare,
            IF((
                    SELECT COUNT(1)
                    FROM tbl_song_fav sub
                    WHERE sub.song_id = a.id AND sub.username='".$username."') >0, TRUE, FALSE) isLiked
            FROM tbl_song a WHERE a.title  LIKE '%".$query."%' and a.isdeleted=0  LIMIT ".$offset.",".$limit;

            $query = $this->db->query($sql);
            return $query->result_array();
    }

    // (CASE WHEN b.count_play > 999999 THEN CONCAT(FORMAT(b.count_play/1000000,1),'M') WHEN b.count_play > 999 THEN CONCAT(FORMAT(b.count_play/1000,1),'K') ELSE b.count_play END) countLabel, IF(DATEDIFF(b.idt, CURDATE()) < 14,'NEW','') tagLabel
    public function getGroupTagMemberPlaylist($idGroup,$streamUrlImage,$offset=0,$limit=6)
    {
        $sql = "SELECT
        'playlist' type,
                a.id_member id,CONCAT('".$streamUrlImage."',b.image) imageUrl,b.name title, 'http://musica.tl' linkShare,
                IFNULL((SELECT SUM(sub.count_play) FROM tbl_song sub WHERE sub.id IN (SELECT ss.song_id FROM tbl_playlist_song ss WHERE ss.playlist_id=b.id)),0) countLabel,
                IF(DATEDIFF(b.idt, CURDATE()) < 14,'NEW','') tagLabel
                FROM tbl_group_tag_member a
                INNER JOIN tbl_playlist b ON a.id_member = b.id
            WHERE a.id_group_tag='".$idGroup."'  LIMIT ".$offset.",".$limit;
        
        $query = $this->db->query($sql);
        return $query->result_array();

    }


    public function getAlbumDetail($id,$streamUrlImage,$streamAuthorImage, $username)
    {
        $sql = "SELECT
        a.id,
        'album' `type`,
        a.album_name title,
        CONCAT('".$streamUrlImage."',a.image) imageUrl,
         'http://musica.tl' linkShare,
         IFNULL((SELECT SUM(sub.count_play) FROM tbl_song sub WHERE sub.album_id=a.id),0) playCount,
         (CASE WHEN a.count_like > 999999 THEN CONCAT(FORMAT(a.count_like/1000000,1),'M') WHEN a.count_like > 999 THEN CONCAT(FORMAT(a.count_like/1000,1),'K') ELSE a.count_like END) likesCount,
        (
        SELECT sub.full_name
        FROM tbl_artist sub
        WHERE sub.id = (
        SELECT subx.artist_id
        FROM tbl_album_artist subx
        WHERE subx.album_id=a.id
        LIMIT 1)) authorName,

         CONCAT('".$streamAuthorImage."',(
        SELECT sub.image
        FROM tbl_artist sub
        WHERE sub.id = (
        SELECT subx.artist_id
        FROM tbl_album_artist subx
        WHERE subx.album_id=a.id
        LIMIT 1))) authorImageUrl,
         a.release_date releaseTime,
         a.udt updateTime,
        0 commentsCount,
        IF((
        SELECT COUNT(1)
        FROM tbl_album_fav sub
        WHERE sub.album_id = a.id AND sub.username='".$username."') >0, TRUE, FALSE) isLiked
        FROM tbl_album a
        WHERE a.id='".$id."' ";
        
        $query = $this->db->query($sql);
        return $query->row_array();

    }


     public function getPlaylistDetail($id,$streamUrlImage,$streamAuthorImage, $username)
    {
        $sql = "SELECT
        a.id,
        'playlist' `type`,
        IFNULL(a.description,'') description,
        a.name title,
        CONCAT('".$streamUrlImage."',a.image) imageUrl,
         'http://musica.tl' linkShare,
         IFNULL((SELECT SUM(sub.count_play) FROM tbl_song sub WHERE sub.id IN (SELECT ss.song_id FROM tbl_playlist_song ss WHERE ss.playlist_id=a.id)),0) playCount,
         (CASE WHEN a.count_like > 999999 THEN CONCAT(FORMAT(a.count_like/1000000,1),'M') WHEN a.count_like > 999 THEN CONCAT(FORMAT(a.count_like/1000,1),'K') ELSE a.count_like END) likesCount,
        (
        SELECT sub.name
        FROM tbl_user sub
        WHERE sub.username = a.created_by) authorName,

        CONCAT('".$streamAuthorImage."',(
        SELECT sub.avatar_img
        FROM tbl_user sub
        WHERE sub.username = a.created_by)) authorImageUrl,
         a.created_date releaseTime,
         a.udt updateTime,
        0 commentsCount,
        IF((
        SELECT COUNT(1)
        FROM tbl_playlist_fav sub
        WHERE sub.playlist_id = a.id AND sub.username='".$username."') >0, TRUE, FALSE) isLiked
        FROM tbl_playlist a
        WHERE a.id='".$id."' ";
        
        $query = $this->db->query($sql);
        return $query->row_array();

    }

    function getAlbumSong($id,$username,$streamUrlImage,$streamUrlAudio,$streamUrlLyric){
        $sql="SELECT
            a.id,a.title,a.artist_id artistId,(SELECT sub.full_name FROM tbl_artist sub WHERE sub.id=a.artist_id) artistName,
            a.album_id albumId,(SELECT sub.album_name FROM tbl_album sub WHERE sub.id=a.album_id) albumName,
            ROUND(a.duration*1000,0) duration,
             count_like likesCount, 
            count_play playCount,
            CONCAT('".$streamUrlImage."',(SELECT sub.image FROM tbl_album sub WHERE sub.id=a.album_id)) imageUrl,
            CONCAT('".$streamUrlAudio."',a.file) streamUrl,
            CONCAT('".$streamUrlLyric."',a.file_lyric) lyricUrl,
            '".$id."' sectionId,
            IF(song_type='PREMIUM',TRUE,FALSE) isPremium,
            IF(a.file_instrument IS NULL OR a.file_lyric IS NULL,0,1) canSing,
            'http://musica.tl' linkShare,
            IF((
                    SELECT COUNT(1)
                    FROM tbl_song_fav sub
                    WHERE sub.song_id = a.id AND sub.username='".$username."') >0, TRUE, FALSE) isLiked
            FROM tbl_song a WHERE a.album_id = '".$id."' ";

            $query = $this->db->query($sql);
            return $query->result_array();
    }

     function getPlaylistSong($id,$username,$streamUrlImage,$streamUrlAudio,$streamUrlLyric){
        $sql="SELECT
            a.id,a.title,a.artist_id artistId,(SELECT sub.full_name FROM tbl_artist sub WHERE sub.id=a.artist_id) artistName,
            a.album_id albumId,(SELECT sub.album_name FROM tbl_album sub WHERE sub.id=a.album_id) albumName,
            ROUND(a.duration*1000,0) duration,
            count_like likesCount, 
            count_play playCount,
            CONCAT('".$streamUrlImage."',(SELECT sub.image FROM tbl_album sub WHERE sub.id=a.album_id)) imageUrl,
            CONCAT('".$streamUrlAudio."',a.file) streamUrl,
            CONCAT('".$streamUrlLyric."',a.file_lyric) lyricUrl,
            '".$id."' sectionId,
            IF(song_type='PREMIUM',TRUE,FALSE) isPremium,
            IF(a.file_instrument IS NULL OR a.file_lyric IS NULL,0,1) canSing,
            'http://musica.tl' linkShare,
            IF((
                    SELECT COUNT(1)
                    FROM tbl_song_fav sub
                    WHERE sub.song_id = a.id AND sub.username='".$username."') >0, TRUE, FALSE) isLiked
            FROM tbl_playlist_song b 
            INNER JOIN tbl_song a ON a.id=b.song_id

            WHERE b.playlist_id = '".$id."'";

            $query = $this->db->query($sql);
            return $query->result_array();
    }


    function getSongData($id){
        $sql = "SELECT
            a.id,a.`file`,a.song_type,a.title
            FROM tbl_song a WHERE a.id='".$id."'";

        $query = $this->db->query($sql);
        return $query->row_array();
    }


     function getSongDetail($id,$username){
        $sql="SELECT
            a.id,a.title,a.artist_id artistId,(SELECT sub.full_name FROM tbl_artist sub WHERE sub.id=a.artist_id) artistName,
            a.album_id albumId,(SELECT sub.album_name FROM tbl_album sub WHERE sub.id=a.album_id) albumName,
            ROUND(a.duration*1000,0) duration,'' durationText, 
            (CASE WHEN a.count_like > 999999 THEN CONCAT(FORMAT(a.count_like/1000000,1),'M') WHEN a.count_like > 999 THEN CONCAT(FORMAT(a.count_like/1000,1),'K') ELSE a.count_like END) likesCount,
            (CASE WHEN a.count_play > 999999 THEN CONCAT(FORMAT(a.count_play/1000000,1),'M') WHEN a.count_play > 999 THEN CONCAT(FORMAT(a.count_play/1000,1),'K') ELSE a.count_play END) playCount,
            (SELECT sub.image FROM tbl_album sub WHERE sub.id=a.album_id) imageUrl,
            a.file streamUrl,
            a.file_lyric lyricUrl,
            '".$id."' sectionId,
            IF(song_type='PREMIUM',TRUE,FALSE) isPremium,
            'http://musica.tl' linkShare,
            IF(a.file_instrument IS NULL OR a.file_lyric IS NULL,0,1) canSing,
            IF(a.file_lyric_duet IS NULL,FALSE,TRUE) canDuet,
            IF((
                    SELECT COUNT(1)
                    FROM tbl_song_fav sub
                    WHERE sub.song_id = a.id AND sub.username='".$username."') >0, TRUE, FALSE) isLiked
            FROM tbl_song a WHERE a.id = '".$id."'";

            $query = $this->db->query($sql);
            return $query->row_array();
    }


    function getKaraokeConfigSong($id){
        $sql="SELECT
            a.id songId,a.title songTitle,
            a.file audioUrl,
            a.file_instrument instrumentUrl,
            a.file_lyric lyricUrl,
            ROUND(a.duration*1000,0) duration,
            IF(song_type='PREMIUM',TRUE,FALSE) isPremium,
            IF(a.file_instrument IS NULL OR a.file_lyric IS NULL,0,1) canSing,
            IF(a.file_lyric_duet IS NULL,FALSE,TRUE) canDuet
            FROM tbl_song a WHERE a.id = '".$id."'";

            $query = $this->db->query($sql);
            return $query->row_array();
    }

    function isSongLiked($id,$username){

        $this->db->select('count(1) total_found');
        $this->db->from('tbl_song_fav');
        $this->db->where('song_id', $id);
        $this->db->where('username', $username);
        $query = $this->db->get();
        $result = $query->row_array();
        if (isset($result['total_found'])) {
            return $result['total_found'] > 0;
        } else {
            return false;
        }
    }

    function setSongLike($id,$username){
        $data = array('iby'=>$username,'username'=>$username,'song_id'=>$id);
        $this->db->insert('tbl_song_fav', $data);
        if($this->db->affected_rows() > 0){
            $this->db->set('count_like', 'count_like+1', FALSE);
            $this->db->where("id", $id);
            $this->db->update("tbl_song");
            return $this->db->affected_rows() > 0;
        }else{
            return false;
        }
    }

     function setSongUnlike($id,$username){
        $this->db->where('song_id', $id);
        $this->db->where('username', $username);
        $this->db->delete('tbl_song_fav');
          if($this->db->affected_rows() > 0){
            $this->db->set('count_like', 'count_like-1', FALSE);
            $this->db->where("id", $id);
            $this->db->update("tbl_song");
            return $this->db->affected_rows() > 0;
        }else{
            return false;
        }
    }


    function isAlbumLiked($id,$username){

        $this->db->select('count(1) total_found');
        $this->db->from('tbl_album_fav');
        $this->db->where('album_id', $id);
        $this->db->where('username', $username);
        $query = $this->db->get();
        $result = $query->row_array();
        if (isset($result['total_found'])) {
            return $result['total_found'] > 0;
        } else {
            return false;
        }
    }

    function setAlbumLike($id,$username){
        $data = array('iby'=>$username,'username'=>$username,'album_id'=>$id);
        $this->db->insert('tbl_album_fav', $data);
        if($this->db->affected_rows() > 0){
            $this->db->set('count_like', 'count_like+1', FALSE);
            $this->db->where("id", $id);
            $this->db->update("tbl_album");
            return $this->db->affected_rows() > 0;
        }else{
            return false;
        }
    }

     function setAlbumUnlike($id,$username){
        $this->db->where('album_id', $id);
        $this->db->where('username', $username);
        $this->db->delete('tbl_album_fav');

        if($this->db->affected_rows() > 0){
            $this->db->set('count_like', 'count_like-1', FALSE);
            $this->db->where("id", $id);
            $this->db->update("tbl_album");
            return $this->db->affected_rows() > 0;
        }else{
            return false;
        }
    }


     function isPlaylistLiked($id,$username){

        $this->db->select('count(1) total_found');
        $this->db->from('tbl_playlist_fav');
        $this->db->where('playlist_id', $id);
        $this->db->where('username', $username);
        $query = $this->db->get();
        $result = $query->row_array();
        if (isset($result['total_found'])) {
            return $result['total_found'] > 0;
        } else {
            return false;
        }
    }

    function setPlaylistLike($id,$username){
        $data = array('iby'=>$username,'username'=>$username,'playlist_id'=>$id);
        $this->db->insert('tbl_playlist_fav', $data);
        if($this->db->affected_rows() > 0){
            $this->db->set('count_like', 'count_like+1', FALSE);
            $this->db->where("id", $id);
            $this->db->update("tbl_playlist");
            return $this->db->affected_rows() > 0;
        }else{
            return false;
        }
    }

     function setPlaylistUnlike($id,$username){
        $this->db->where('playlist_id', $id);
        $this->db->where('username', $username);
        $this->db->delete('tbl_playlist_fav');

        if($this->db->affected_rows() > 0){
            $this->db->set('count_like', 'count_like-1', FALSE);
            $this->db->where("id", $id);
            $this->db->update("tbl_playlist");
            return $this->db->affected_rows() > 0;
        }else{
            return false;
        }
    }


    public function getKaraokeDetail($id)
    {
        $sql = "SELECT
        a.id,
        IFNULL(a.description,'') description,
        IFNULL(a.name,(SELECT sub.title from tbl_song sub where sub.id=a.song_id)) title,
        a.image imageUrl,
        a.video videoUrl,
        IFNULL(a.lyric,(SELECT sub.file_lyric from tbl_song sub where sub.id=a.song_id)) lyricUrl,
        a.song_id songId,
         (CASE WHEN a.count_play > 999999 THEN CONCAT(FORMAT(a.count_play/1000000,1),'M') WHEN a.count_play > 999 THEN CONCAT(FORMAT(a.count_play/1000,1),'K') ELSE a.count_play END) viewsCount,
         (CASE WHEN a.count_like > 999999 THEN CONCAT(FORMAT(a.count_like/1000000,1),'M') WHEN a.count_like > 999 THEN CONCAT(FORMAT(a.count_like/1000,1),'K') ELSE a.count_like END) likesCount,
        (
        SELECT sub.name
        FROM tbl_user sub
        WHERE sub.username = a.created_by) authorName,
        (
        SELECT sub.avatar_img
        FROM tbl_user sub
        WHERE sub.username = a.created_by) authorImageUrl,
        a.count_comment commentsCount,
        ROUND(a.duration*1000,0) duration,
        a.karaoke_type karaokeType,
        a.sing_part singPart,
        (SELECT IF(sub.file_instrument IS NOT NULL AND sub.file_lyric is NOT NULL,1,0) FROM tbl_song sub WHERE sub.id = a.song_id) canSing,
        IF(LOWER(a.karaoke_type)='solo' OR LOWER(a.karaoke_type)='duet',1,0) canDuet
        FROM tbl_karaoke a
        WHERE a.id='".$id."' ";
        
        $query = $this->db->query($sql);
        return $query->row_array();
    }


    function isKaraokeLiked($id,$username){

        $this->db->select('count(1) total_found');
        $this->db->from('tbl_karaoke_fav');
        $this->db->where('karaoke_id', $id);
        $this->db->where('username', $username);
        $query = $this->db->get();
        $result = $query->row_array();
        if (isset($result['total_found'])) {
            return $result['total_found'] > 0;
        } else {
            return false;
        }
    }

    function setKaraokeLike($id,$username){
        $data = array('iby'=>$username,'username'=>$username,'karaoke_id'=>$id);
        $this->db->insert('tbl_karaoke_fav', $data);
        if($this->db->affected_rows() > 0){
            $this->db->set('count_like', 'count_like+1', FALSE);
            $this->db->where("id", $id);
            $this->db->update("tbl_karaoke");
            return $this->db->affected_rows() > 0;
        }else{
            return false;
        }
    }

     function setKaraokeUnlike($id,$username){
        $this->db->where('karaoke_id', $id);
        $this->db->where('username', $username);
        $this->db->delete('tbl_karaoke_fav');

        if($this->db->affected_rows() > 0){
            $this->db->set('count_like', 'count_like-1', FALSE);
            $this->db->where("id", $id);
            $this->db->update("tbl_karaoke");
            return $this->db->affected_rows() > 0;
        }else{
            return false;
        }
    }


    public function getKaraokeList($streamUrlImage,$videoUrl,$offset=0,$limit=6)
    {
        $sql = "SELECT
                    a.id,
                    a.name title,
                    'http://musica.tl' linkShare,
                    CONCAT('".$streamUrlImage."',a.image) imageUrl,
                    CONCAT('".$videoUrl."',a.video) videoUrl,
                     (CASE WHEN a.count_play > 999999 THEN CONCAT(FORMAT(a.count_play/1000000,1),'M') WHEN a.count_play > 999 THEN CONCAT(FORMAT(a.count_play/1000,1),'K') ELSE a.count_play END) viewsCount,
                    (
                    SELECT sub.name
                    FROM tbl_user sub
                    WHERE sub.username = a.created_by) subtitle,
                    a.duration
                    FROM tbl_karaoke a WHERE a.isdeleted = 0
                     LIMIT ".$offset.",".$limit;
        
        $query = $this->db->query($sql);
        return $query->result_array();

    }


    function recordKaraokePlayed($data){
        $this->db->insert('tbl_karaoke_played', $data);
        if($this->db->affected_rows() > 0){
            $this->db->set('count_play', 'count_play+1', FALSE);
            $this->db->where("id", $data['karaoke_id']);
            $this->db->update("tbl_karaoke");
            return $this->db->affected_rows() > 0;
        }else{
            return false;
        }
    }

    function recordSongPlayed($data){
        $this->db->insert('tbl_song_played', $data);
        // if(!empty($data['song_id'])){
        //     $sqlUpdatePlaylistAlbum = "UPDATE table_name
        //                             SET column1=value, column2=value2,...
        //                             WHERE some_column=some_value";
        // }
        
        $this->db->query();
        if($this->db->affected_rows() > 0){
            $this->db->set('count_play', 'count_play+1', FALSE);
            $this->db->where("id", $data['song_id']);
            $this->db->update("tbl_song");
            return $this->db->affected_rows() > 0;
        }else{
            return false;
        }
    }


    public function getPlanPacketList()
    {
        $this->db->select("plan_type,plan_title,validity_seconds,price,recurring,feature");
        $this->db->from("tbl_subscribe_plan a");
        $this->db->where("isdeleted", 0);
        $this->db->where("isactive", 1);
        $this->db->where("plan_type !=", 'free');
        $query = $this->db->get();
        return $query->result_array();
    }

     public function getPlanPacket($id)
    {
        $this->db->select("plan_type,plan_title,validity_seconds,price,recurring,feature");
        $this->db->from("tbl_subscribe_plan a");
        $this->db->where("plan_type", $id);
        $query = $this->db->get();
        return $query->row_array();
    }

    public function insertSubscribeTransaction($data)
    {
        $this->db->insert('tbl_subscribe', $data);
        return $this->db->insert_id();
    }

     public function updateSubscribeTransaction($id,$data)
    {
        $this->db->where('id', $id);
        $this->db->update('tbl_subscribe', $data);
        return $this->db->affected_rows() > 0;
    }

    public function isPaymentOTPMatch($otp, $username,$transactionId)
    {
        $this->db->select('count(1) total_found');
        $this->db->from('tbl_subscribe');
        $this->db->where('username', $username);
        $this->db->where('otp', $otp);
        $this->db->where('id', $transactionId);
        $query = $this->db->get();
        $result = $query->row_array();
        if (isset($result['total_found'])) {
            return $result['total_found'] > 0;
        } else {
            return false;
        }
    }

    public function paymentOtpInfo($transactionId)
    {
        $this->db->select('id,plan_type,payment_required,validity_seconds');
        $this->db->from('tbl_subscribe');
        $this->db->where('id', $transactionId);
        $query = $this->db->get();
        return $query->row_array();
    }

      public function insertKaraoke($data)
    {
        $this->db->insert('tbl_karaoke', $data);
        $insert_id = $this->db->insert_id();
        return  $insert_id;
    }

}
                        
/* End of file User.php */
    
                        