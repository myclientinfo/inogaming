<?php
   /**
	* Copyright (c) 2003 Brian E. Lozier (brian@massassi.net)
	*
	* set_vars() method contributed by Ricardo Garcia (Thanks!)
	*
	* Permission is hereby granted, free of charge, to any person obtaining a copy
	* of this software and associated documentation files (the "Software"), to
	* deal in the Software without restriction, including without limitation the
	* rights to use, copy, modify, merge, publish, distribute, sublicense, and/or
	* sell copies of the Software, and to permit persons to whom the Software is
	* furnished to do so, subject to the following conditions:
	*
	* The above copyright notice and this permission notice shall be included in
	* all copies or substantial portions of the Software.
	*
	* THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
	* IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
	* FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
	* AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
	* LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING
	* FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS
	* IN THE SOFTWARE.
    */

class Template {

	var $vars; 								// Holds all the template variables
	var $path = "/templates/";			 					// Path to the template
	var $file;							 	// File of the template
	//var $paths = 'templates';
	var $success;
	var $outputBuffer;
	static $globalVars = array();

	/*
	 * Constructor
	 *
	 * @param string $path the path to the templates
	 *
	 * @return void
	 */

	function Template($file, $path = NULL)
	{
		$this->setFile($file);
		$this->setPath($path);
		$this->outputBuffer = NULL;
		//$this->paths = preg_split("/[:;]/", ini_get('include_path'));
		$this->vars = array();
	}

	/*
	 * Set the path to the template files.
	 *
	 * @param string $path path to template files
	 *
	 * @return void
	 */

	function setPath($path)
	{
		if (strlen($path) > 0 && substr($path, strlen($path), 1) != '/') $path .= '/';
		if (substr($path, 0, 1) == '/') $path = substr($path, 1);
       		$this->path = $path;
    	}

	/*
	 * Set the name of the template file.
	 *
	 * @param string $file - file of the template
	 *
	 * @return void
	 */

	function setFile($file, $path = NULL)
	{
		if ($path != NULL) $this->setPath($path);
		$this->file = $file;
	}

	/*
	 * Set a template variable.
	 *
	 * @param string $name name of the variable to set
	 * @param mixed $value the value of the variable
	 *
	 * @return void
	 */

	function set($name, $value)
	{
		if ($this) $this->vars[$name] = $value;
		Template::$globalVars[$name] = $value;
	}

	/*
	 * Set a template variable.
	 *
	 * @param string $name name of the variable to set
	 * @param mixed $value the value of the variable
	 *
	 * @return void
	 */

	function get($name)
	{
		if ($this && array_key_exists($name, $this->vars))
		{
			return $this->vars[$name];
		}
		else if (array_key_exists($name, Template::$globalVars))
		{
			return Template::$globalVars[$name];
		}
		else
		{
			return false;
		}
	}

	/*
	 * Set a bunch of variables at once using an associative array.
	 *
	 * @param array $vars array of vars to set
	 * @param bool $clear whether to completely overwrite the existing vars
	 *
	 * @return void
	 */

	function getVars()	// Use only for debugging.
	{
		$vars = array();
		$vars = array_merge($vars, Template::$globalVars);
		if ($this) $vars = array_merge($vars, $this->vars);

		echo "<pre>";
		echo "Var Count: " . count($this->vars) . "\n";
		print_r($vars);
		echo "</pre>";
		exit;
	}

	function setVars($vars, $clear = false)
	{
		if($clear && $this)
		{
           $this->vars = $vars;
		}
		elseif(is_array($vars))
		{
			if ($this) $this->vars = array_merge($this->vars, $vars);
			Template::$globalVars = array_merge(Template::$globalVars, $vars);
		}
	}

	function callbackBuffer($buffer)
	{
		return $buffer;
	}

	function startBuffer($name)
	{
		$this->outputBuffer = $name;
		ob_start("Template::callbackBuffer");
		$this->vars[$name] = $value;
	}

	function stopBuffer()
	{
		if ($this->outputBuffer !== NULL)
			$this->vars[$this->outputBuffer] = ob_get_contents();
		ob_end_clean();
	}

	/**
	 * Open, parse, and return the template file.
	 *
	 * @param string string the template file name
	 *
	 * @return string
	 */

	function fetch()
	{
		if (file_exists(TEMPLATE_PATH . $this->path . $this->file . ".tpl.php"))
		{
			//extract(Template::$globalVars);
			extract($this->vars);													// Extract the vars to local namespace
			
			ob_start();																// Start output buffering
			$this->success = include(TEMPLATE_PATH . $this->path . $this->file . ".tpl.php");  		// Include the file
			$contents = ob_get_contents();											// Get the contents of the buffer
			ob_end_clean();
        													// End buffering and discard
			
			//Did we succeed?				
			$this->success = ($this->success !== false && $contents != '');
			
			if ($this->success)
				return $contents;														// Return the contents
			else
				return "<!-- ERROR: Template returned an error -->";
		}
		if (DEBUG) return 'ERROR: Could not locate template file "' . $this->path . $this->file . '.tpl.php"';
		else	   return '<!-- ERROR: Could not locate template file "' . $this->path . $this->file . '.tpl.php"-->';
	}
}

/**
 * An extension to Template that provides automatic caching of
 * template contents.
 * @package
 * @author Joshua Duck
 * @todo This class must be in a separate file.
 */
class CachedTemplate extends Template {
	/**
	 * @access private
	 * @var string
	 */
	private $cacheFile;
	
	/**#@+
	 * @access private
	 * @var int
	 */
	private $cacheId;
	private $expire;
	/**#@-*/
	
	/**
	 * @access private
	 * @var boolean
	 */
	private $cached;
	
	/**
	 * @access private
	 * @var date
	 */
	private $lastModified;

	/**
	 * Constructor.
	 * @access public
	 * @param string file
	 * @param string path 		Optional(default = null) Path to template files.
	 * @param string cacheId 	Optional(default = null) Unique cache identifier.
	 * @param int expire 		Optional(default = 900) Number of seconds the cache will live.
	 * @return void
	 * @author
	 */
	public function __construct($file, $path = null, $cacheId = null, $expire = 900)
	{
		if (DEBUG) $expire = 0;

		$this->expire = $expire;
		$this->cacheId = $cacheId;
		if ($this->cacheId) $this->cacheFile = md5($this->cacheId . $path . $file . DOMAIN_NAME);
		parent::__construct($file, $path);
	}

	/**
	 * Delete the cache is if exists.
	 * @access public
	 * @return boolean
	 * @author
	 */
	public function clearCache()
	{
		if ($this->isCached())
		{
			@unlink(CACHE_PATH . $this->cacheFile);
		}
		$this->cached = false;
		return true;
	}
	
	/**
	 * This function returns a cached copy of a template (if it exists),
	 * otherwise, it parses it as normal and caches the content.
	 * @access public
	 * @param file string the template file
	 * @return string
	 * @author
	 */
	public function fetchCache()
	{
		//Is it cached?
		if($this->isCached())
		{
			$contents = file_get_contents (CACHE_PATH . $this->cacheFile);
			if ($contents !== false && $contents != '') return $contents;
		}

		//Generate contents
		$contents = $this->fetch($this->file);

		// Write the cache
		if($this->success && $fp = @fopen(CACHE_PATH . $this->cacheFile, 'w'))
		{
			fwrite($fp, $contents);
			fclose($fp);
		}

		return $contents;
	}//End of fetchCache()
	
	/**
	 * Test to see whether the currently loaded cacheId has a valid
	 * corresponding cache file.
	 * @access public
	 * @return bool
	 * @author
	 */
	public function isCached()
	{
		if($this->cached)
			return true;

       	// Passed a cacheId?
		if (!$this->cacheId)
			return false;

	 	// Cache file exists?
		if(!file_exists(CACHE_PATH . $this->cacheFile))
			return false;

		// Can get the time of the file?
		if (!($this->lastModified = filemtime(CACHE_PATH . $this->cacheFile)))
			return false;

		// Is the file empty
		if (filesize(CACHE_PATH . $this->cacheFile) == 0)
			return false;

		// Cache expired?
		if (($this->lastModified + $this->expire) < time())
		{
			@unlink(CACHE_PATH . $this->cacheId);
			return false;
		}
		else
		{
			
			 // Cache the results of this isCached() call.  Why?  So
			 // we don't have to double the overhead for each template.
			 // If we didn't cache, it would be hitting the file system
			 // twice as much (file_exists() & filemtime() [twice each]).
			$this->cached = true;
			return true;
		}
	}//End of isCached()
	
	/**
	 * This will send the cache headers.
	 * @access public
	 * @return void
	 * @author
	 */
	public function sendCacheHeaders()
	{
		if($this->isCached())
		{
			header("Last-Modified: " . gmdate("D, d M Y H:i:s", $this->lastModified) . " GMT");
			header("Expires: " . gmdate("D, d M Y H:i:s", $this->lastModified + $this->expire) . " GMT");
			header("Cache-Control: max-age=" . ($this->expire + $this->lastModified - time()));
		}
		else
		{
			header("Expires: " . gmdate("D, d M Y H:i:s", time() + $this->expire) . " GMT");
			header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
			header("Cache-Control: max-age=" . $this->expire);
		}
	}//End of sendCacheHeaders()
	
	/**
	 * This overrides the parent's setPath() method.
	 * @access public
	 * @param string path
	 * @return void
	 * @author
	 */
	public function setPath($path)
	{
		parent::setPath($path);
		$this->cached = false;
		if ($this->cacheId) $this->cacheFile = md5($this->cacheId . $this->path. $this->file . DOMAIN_NAME);
	}
	
	/**
	 * Set the name of the template file. Overwrite from base class.
	 * @access public
	 * @param string $file - file of the template
	 * @return void
	 * @author
	 */
	public function setFile($file, $path = NULL)
	{
		parent::setFile($file, $path);
		$this->cached = false;
		if ($this->cacheId) $this->cacheFile = md5($this->cacheId . $this->path. $this->file . DOMAIN_NAME);
	}
	
}//End of CachedTemplate class

?>