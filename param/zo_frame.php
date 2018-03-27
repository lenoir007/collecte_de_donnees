<?php
	class zo
	{
		function is_isset($a)
		{
			$ans = false;
			$erro = array(); $contents = array();
			if (is_array($a)) {
				$ans = true;
				foreach ($a as $key => $detail) {
					$cu = $detail;
					if ( gettype($key) != "string") {
						$key = $detail;
						$cu = "";
					}
					!isset($_REQUEST[$key]) && $erro[$key] = "isset";
					if (is_array($cu)) {
						if (isset($cu["not_empty"]) && $cu["not_empty"] && !isset($erro[$key])) {
							empty($_REQUEST[$key]) && $erro[$key] = "empty";
						}
						if (!isset($erro[$key])) {
							if (isset($cu["type_value"])) {
								!($this -> type_value($_REQUEST[$key],$cu["type_value"])) && $erro[$key] = false;	
							}
							if (isset($cu["limite_char"])) {
								$lim = explode("-", $cu["limite_char"]); $min_char = 1;$max_char = 255; 
								$total_char = strlen($_REQUEST[$key]);
								if (count($lim) > 1) {
									$min_char = $lim[0] > $lim[1]?$lim[1]:$lim[0]; $max_char = $lim[0] < $lim[1]?$lim[1]:$lim[0];
								}
								elseif (count($lim) == 1 && $lim[0] < $max_char) {$min_char = $lim[0];}
								if ($total_char > $max_char || $total_char < $min_char) { $erro[$key] = "null";}
							}
						}
					}
					!isset($erro[$key]) && $contents[$key] = $_REQUEST[$key];
				}
			}
			if ($ans) {
				return array(
							'errors' => $erro,
							'contents' => $contents,
					    );
			}
			return $ans;
		}
		function file($name,$path)
		{
			$p = false;
			if (isset($_FILES[$name])){
				$extensions_ok = array('jpg', 'png', 'gif', 'jpeg');
				$tailleMax = 8000000;
				$profil = $_FILES[$name];
				$tailleOctet = $profil['size'];
				$extension = substr($profil['name'], strrpos($profil['name'], '.') + 1);
		
				//verification de l'extension
				if(!in_array($extension, $extensions_ok)){$p = false;}
				//verification de la taille de l'image
				elseif($tailleOctet > $tailleMax){$p = null;}
				else
				{
					$name_file_profil = 'electro_'.$name.'_'.time() .'.' . $extension;
					$cheminServeur = $path.$name_file_profil;
	
					if(move_uploaded_file($profil['tmp_name'], $cheminServeur))
					{
						$name = $name_file_profil;
						$p = true;
					}
					else{$p = false;}
				}
			}
			return ["name" => $name,"action" => $p];

		}
		function type_value($v,$t)
		{
			$r = false;
			switch ($t) {
				case "digital" : $r = "#^[0-9]+$#"; break;
				case "alpha" : $r = "#^[a-zA-Z]+$#"; break;
				case "alpha_utf" : $r = "#^[a-zA-Z éêèëàäâùüûôöîïÿŷçÉÈËÊÙÜÛÂÄÀÖÔŶŸÇ_.-]+$#"; break;
				case "digital_alpha" : $r = "#^[a-zA-Z0-9 éêèëàäâùüûôöîïÿŷçÉÈËÊÙÜÛÂÄÀÖÔŶŸÇÏÎ_.-]+$#"; break;
				case  "email": $r = "#^[a-zA-Z0-9._-]+@[a-zA-Z-._]+\.[a-z]{2,6}$#"; break;
				case "login": $r = "#^[a-zA-Z0-9]+$#"; break;
			}
			if ($r && preg_match($r, $v)) {$r = true;}else{$r = false;}
			return $r;
		}
		function exclu_db_key($l)
		{
			$blacklist = array("ADD", "EXTERNAL", "PROCEDURE", "ALL", "FETCH", "PUBLIC", "ALTER", "FILE", "RAISERROR", "AND", "FILLFACTOR", "READ", "ANY", "FOR", "READTEXT", "AS", "FOREIGN", "RECONFIGURE", "ASC", "FREETEXT", "REFERENCES", "AUTHORIZATION", "FREETEXTTABLE", "REPLICATION", "BACKUP", "FROM", "RESTORE", "BEGIN", "FULL", "RESTRICT", "BETWEEN", "FUNCTION", "RETURN", "BREAK", "GOTO", "REVERT", "BROWSE", "GRANT", "REVOKE", "BULK", "GROUP", "RIGHT", "BY", "HAVING", "ROLLBACK", "CASCADE", "HOLDLOCK", "ROWCOUNT", "CASE", "IDENTITY", "ROWGUIDCOL", "CHECK", "IDENTITY_INSERT", "RULE", "CHECKPOINT", "IDENTITYCOL", "SAVE", "CLOSE", "IF", "SCHEMA", "CLUSTERED", "INSERT", "IN", "SECURITYAUDIT", "COALESCE", "INDEX", "SELECT", "COLLATE", "INNER", "SEMANTICKEYPHRASETABLE", "COLUMN", "SEMANTICSIMILARITYDETAILSTABLE", "COMMIT", "INTERSECT", "SEMANTICSIMILARITYTABLE", "COMPUTE", "INTO", "SESSION_USER", "CONSTRAINT", "SET", "CONTAINS", "JOIN", "SETUSER", "CONTAINSTABLE", "SHUTDOWN", "CONTINUE", "KILL", "SOME", "CONVERT", "LEFT", "STATISTICS", "CREATE", "LIKE", "SYSTEM_USER", "CROSS", "LINENO", "TABLE", "CURRENT", "LOAD", "TABLESAMPLE", "CURRENT_DATE", "MERGE", "TEXTSIZE", "CURRENT_TIME", "NATIONAL", "THEN", "CURRENT_TIMESTAMP", "NOCHECK", "TO", "CURRENT_USER", "NONCLUSTERED", "TOP", "CURSOR", "NOT", "TRAN", "DATABASE", "NULL", "TRANSACTION", "DBCC", "NULLIF", "TRIGGER", "DEALLOCATE", "OF", "TRUNCATE", "DECLARE", "OFF", "TRY_CONVERT", "DEFAULT", "OFFSETS", "TSEQUAL", "DELETE", "ON", "UNION", "DENY", "OPEN", "UNIQUE", "DESC", "OPENDATASOURCE", "UNPIVOT", "DISK", "OPENQUERY", "UPDATE", "DISTINCT", "OPENROWSET", "UPDATETEXT", "DISTRIBUTED", "OPENXML", "USE", "DOUBLE", "OPTION", "USER", "DROP", "OR", "VALUES", "DUMP", "ORDER", "VARYING", "ELSE", "OUTER", "VIEW", "END", "OVER", "WAITFOR", "ERRLVL", "PERCENT", "WHEN", "ESCAPE", "PIVOT", "WHERE", "EXCEPT", "PLAN", "WHILE", "EXEC", "PRECISION", "WITH", "EXECUTE", "PRIMARY", "WITHIN", "GROUP", "EXISTS", "PRINT", "WRITETEXT", "EXIT", "PROC");

	  		if (is_array($l)) {
				$s = array();
				foreach ($l as $key => $value) {
					$s[$key] = $value;
					foreach ($blacklist as $word){
						$s[$key] = str_replace ($word, "", $s[$key]);
					}
				}
			}
			else{
				$s = $l;
				foreach ($blacklist as $word){
					$s = str_replace ($word, "", $s);
				}
			}
			return $s;
		}
	}