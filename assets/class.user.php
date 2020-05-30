<?php

include('dbconfig.php');

class USER
{	
	private $conn;
	public function __construct()
	{
		$database = new Database();
		$db = $database->dbConnection();
		$this->conn = $db;
    }
	public function runQuery($sql)
	{
		$stmt = $this->conn->prepare($sql);
		return $stmt;
	}
	public function register_account($uname,$firstname,$lastname,$upass1,$umail1)
	{
		try
		{
			$new_password = password_hash($upass1, PASSWORD_DEFAULT);

			$stmt = $this->conn->prepare("INSERT INTO users(user_name,user_firstname,user_lastname,user_pass,user_email) 
		                          VALUES(:uname, :firstname, :lastname, :upass1, :umail1 )");									  
			$stmt->bindparam(":uname", $uname);
			$stmt->bindparam(":firstname", $firstname);
			$stmt->bindparam(":lastname", $lastname);
			$stmt->bindparam(":upass1", $new_password);
			$stmt->bindparam(":umail1", $umail1);
									  
			$stmt->execute();	
			
			return $stmt;	
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}				
	}

	public function edit_account_info($uname,$firstname,$lastname,$umail)
	{
		try
		{
			$user_id = $_SESSION['user_session']['user_id'];
			
			$stmt = $this->conn->prepare("UPDATE users 

				SET user_name = :uname, 
				 user_firstname = :firstname,
				 user_lastname = :lastname,
				 user_email = :umail

				WHERE user_id =:user_id");

			$stmt->bindparam(":uname", $uname);
			$stmt->bindparam(":firstname", $firstname);
			$stmt->bindparam(":lastname", $lastname);
			$stmt->bindparam(":umail", $umail);
			$stmt->bindparam(":user_id", $user_id);
									  
			$stmt->execute();	
			
			return $stmt;

		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}				
	}

	public function edit_account_pass($new_password)
	{
		try
		{
			$user_id = $_SESSION['user_session']['user_id'];
			
			$stmt = $this->conn->prepare("UPDATE users 

				SET user_pass = :new_password

				WHERE user_id =:user_id");

			$stmt->bindparam(":new_password", $new_password);
			$stmt->bindparam(":user_id", $user_id);
									  
			$stmt->execute();	
			
			return $stmt;

		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}				
	}

	public function create_ebook($title,$creator,$new_subject,$publisher,$date,$identifiere,$language,$relation,$rights,$cover_image_name)
	{
		try
		{
			$user_id = $_SESSION['user_session']['user_id'];

			$stmt = $this->conn->prepare("INSERT INTO ebooks(user_id,ebook_title,ebook_creator,ebook_subject,ebook_publisher,ebook_date,ebook_identifiere,ebook_language,ebook_relation,ebook_rights,ebook_cover_image)VALUES(:user_id, :title, :creator, :subject, :publisher, :ebook_date, :identifiere, :language, :relation, :rights, :cover_image )");									  
			$stmt->bindparam(":user_id", $user_id);
			$stmt->bindparam(":title", $title);
			$stmt->bindparam(":creator", $creator);
			$stmt->bindparam(":subject", $new_subject);
			$stmt->bindparam(":publisher", $publisher);
			$stmt->bindparam(":ebook_date", $date);
			$stmt->bindparam(":identifiere", $identifiere);
			$stmt->bindparam(":language", $language);
			$stmt->bindparam(":relation", $relation);
			$stmt->bindparam(":rights", $rights);
			$stmt->bindparam(":cover_image", $cover_image_name);
									  
			$stmt->execute();	
			
			return $stmt;	
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}				
	}

	public function update_ebook($title,$creator,$new_subject,$publisher,$date,$identifiere,$language,$relation,$rights,$cover_image_name)
	{
		try
		{
			$ebook_id = $_SESSION['user_session']['ebook_id'];
			
			$stmt = $this->conn->prepare("UPDATE ebooks 

				SET ebook_title = :title,
					ebook_creator = :creator,
					ebook_subject = :subject,
					ebook_publisher = :publisher,
					ebook_date = :ebook_date,
					ebook_identifiere = :identifiere,
					ebook_language = :language,
					ebook_relation = :relation,
					ebook_rights = :rights,
					ebook_cover_image = :cover_image

				WHERE ebook_id =:ebook_id");

			$stmt->bindparam(":ebook_id", $ebook_id);
			$stmt->bindparam(":title", $title);
			$stmt->bindparam(":creator", $creator);
			$stmt->bindparam(":subject", $new_subject);
			$stmt->bindparam(":publisher", $publisher);
			$stmt->bindparam(":ebook_date", $date);
			$stmt->bindparam(":identifiere", $identifiere);
			$stmt->bindparam(":language", $language);
			$stmt->bindparam(":relation", $relation);
			$stmt->bindparam(":rights", $rights);
			$stmt->bindparam(":cover_image", $cover_image_name);
									  
			$stmt->execute();	
			
			return $stmt;	
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}				
	}
	public function delete_cover($cover_image)
	{
		try
		{
			$ebook_id = $_SESSION['user_session']['ebook_id'];
			
			$stmt = $this->conn->prepare("UPDATE ebooks 

				SET ebook_cover_image = :cover_image

				WHERE ebook_id =:ebook_id");

			$stmt->bindparam(":cover_image", $cover_image);
			$stmt->bindparam(":ebook_id", $ebook_id);
									  
			$stmt->execute();	
			
			return $stmt;	
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}				
	}

	public function create_page($page_id,$ebook_id,$page_name,$page_content)
	{
		try
		{
			$stmt = $this->conn->prepare("INSERT INTO pages(page_id,ebook_id,page_name,page_content)VALUES(:page_id, :ebook_id, :page_name, :page_content)");	

			$stmt->bindparam(":page_id", $page_id);
			$stmt->bindparam(":ebook_id", $ebook_id);
			$stmt->bindparam(":page_name", $page_name);
			$stmt->bindparam(":page_content", $page_content);

			$stmt->execute();	
			
			return $stmt;	
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}				
	}

	public function update_page($page_id,$ebook_id,$page_name,$page_content)
	{
		try
		{
			$stmt = $this->conn->prepare("UPDATE pages 

				SET ebook_id = :ebook_id,
					page_name = :page_name,
					page_content = :page_content

				WHERE page_id =:page_id");

			$stmt->bindparam(":page_id", $page_id);
			$stmt->bindparam(":ebook_id", $ebook_id);
			$stmt->bindparam(":page_name", $page_name);
			$stmt->bindparam(":page_content", $page_content);

			$stmt->execute();	
			
			return $stmt;	
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}				
	}

	public function delete_page($page_id)
	{
		try
		{
			$stmt = $this->conn->prepare("DELETE FROM pages 

				WHERE page_id =:page_id");

			$stmt->bindparam(":page_id", $page_id);
			
			$stmt->execute();	
			
			return $stmt;	
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}				
	}

	public function doLogin($login_uname,$login_umail,$login_upass)
	{
		try
		{
			$stmt = $this->conn->prepare("SELECT * FROM users WHERE user_name=:login_uname OR user_email=:login_umail ");
			$stmt->execute(array(':login_uname'=>$login_uname, ':login_umail'=>$login_umail));
			$userRow=$stmt->fetch(PDO::FETCH_ASSOC);
	      if($stmt->rowCount() == 1)
		  {
			if(password_verify($login_upass, $userRow['user_pass']))
			{
			$_SESSION['user_session'] = array();
			$_SESSION['user_session']['user_id'] = $userRow['user_id'];
            $_SESSION['user_session']['user_type'] = $userRow['user_type'];
            $_SESSION['user_session']['user_name'] = $userRow['user_name'];
            $_SESSION['user_session']['user_firstname'] =$userRow['user_firstname'];
            $_SESSION['user_session']['user_lastname'] = $userRow['user_lastname'];
            $_SESSION['user_session']['user_email'] = $userRow['user_email'];
            $_SESSION['user_session']['user_pass'] = $userRow['user_pass'];
				return true;
			}
			else
			{
				return false;
			}
		  }
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}
	}
	
	public function is_loggedin()
	{
		if(isset($_SESSION['user_session']['user_id']))
		{
			return true;
		}
	}
	
	public function redirect($url)
	{
		header("Location:$url");
	}
	
	public function doLogout()
	{
		session_destroy();
		unset($_SESSION['user_session']['user_id']);
		return true;
	}
}
?>