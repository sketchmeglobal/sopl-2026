<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

/* load the MX core module class */
require dirname(__FILE__).'/Modules.php';

/**
 * Modular Extensions - HMVC
 *
 * Adapted from the CodeIgniter Core Classes
 * @link	http://codeigniter.com
 *
 * Description:
 * This library extends the CodeIgniter router class.
 *
 * Install this file as application/third_party/MX/Router.php
 *
 * @copyright	Copyright (c) 2015 Wiredesignz
 * @version 	5.5
 * 
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 * 
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 * 
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 **/
class MX_Router extends CI_Router
{
	public $module;
	private $located = 0;

	public function fetch_module()
	{
		return $this->module;
	}

	protected function _set_request($segments = array())
	{
		if ($this->translate_uri_dashes === TRUE)
		{
			foreach(range(0, 2) as $v)
			{
				isset($segments[$v]) && $segments[$v] = str_replace('-', '_', $segments[$v]);
			}
		}
		
		$segments = $this->locate($segments);

		if($this->located == -1)
		{
			$this->_set_404override_controller();
			return;
		}

		if(empty($segments))
		{
			$this->_set_default_controller();
			return;
		}
		
		$this->set_class($segments[0]);
		
		if (isset($segments[1]))
		{
			$this->set_method($segments[1]);
		}
		else
		{
			$segments[1] = 'index';
		}
       
		array_unshift($segments, NULL);
		unset($segments[0]);
		$this->uri->rsegments = $segments;
	}
	
	protected function _set_404override_controller()
	{
		$this->_set_module_path($this->routes['404_override']);
	}

	protected function _set_default_controller()
	{
		if (empty($this->directory))
		{
			/* set the default controller module path */
			$this->_set_module_path($this->default_controller);
		}

		parent::_set_default_controller();
		
		if(empty($this->class))
		{
			$this->_set_404override_controller();
		}
	}

	/** Locate the controller **/
	public function locate($segments)
	{
		$this->located = 0;
		$ext = $this->config->item('controller_suffix').EXT;
		$_dbg_uri = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : 'N/A';
		$_dbg_seg_in = implode('/', $segments);
		$_dbg_log = FCPATH.'mx_router_debug.txt';
		file_put_contents($_dbg_log, date('H:i:s')." locate() CALLED | URI={$_dbg_uri} | segs={$_dbg_seg_in}\n", FILE_APPEND);

		/* use module route if available */
		if (isset($segments[0]) && $routes = Modules::parse_routes($segments[0], implode('/', $segments)))
		{
			file_put_contents($_dbg_log, "  parse_routes('{$segments[0]}') matched -> ".implode('/',$routes)."\n", FILE_APPEND);
			$segments = $routes;
		}
		else
		{
			file_put_contents($_dbg_log, "  parse_routes('{$segments[0]}') -> no match\n", FILE_APPEND);
		}

		/* get the segments array elements */
		list($module, $directory, $controller) = array_pad($segments, 3, NULL);
		file_put_contents($_dbg_log, "  module={$module} dir={$directory} ctrl={$controller}\n", FILE_APPEND);

		/* check modules */
		foreach (Modules::$locations as $location => $offset)
		{
			$_dbg_src = $location.$module.'/controllers/';
			file_put_contents($_dbg_log, "  checking is_dir: {$_dbg_src} -> ".(is_dir($_dbg_src)?'YES':'NO')."\n", FILE_APPEND);
			/* module exists? */
			if (is_dir($source = $location.$module.'/controllers/'))
			{
				$this->module = $module;
				$this->directory = $offset.$module.'/controllers/';

				/* module sub-controller exists? */
				if($directory)
				{
					$_dbg_subdir = $source.$directory.'/';
					$_dbg_subfile = $source.ucfirst($directory).$ext;
					file_put_contents($_dbg_log, "  is_dir({$_dbg_subdir}): ".(is_dir($_dbg_subdir)?'YES':'NO')."\n", FILE_APPEND);
					file_put_contents($_dbg_log, "  is_file({$_dbg_subfile}): ".(is_file($_dbg_subfile)?'YES':'NO')."\n", FILE_APPEND);
					/* module sub-directory exists? */
					if(is_dir($source.$directory.'/'))
					{
						$source .= $directory.'/';
						$this->directory .= $directory.'/';

						/* module sub-directory controller exists? */
						if($controller)
						{
							if(is_file($source.ucfirst($controller).$ext))
							{
								$this->located = 3;
								file_put_contents($_dbg_log, "  located=3, return ".implode('/',array_slice($segments,2))."\n", FILE_APPEND);
								return array_slice($segments, 2);
							}
							else $this->located = -1;
						}
					}
					else
					if(is_file($source.ucfirst($directory).$ext))
					{
						$this->located = 2;
						file_put_contents($_dbg_log, "  located=2, return ".implode('/',array_slice($segments,1))."\n", FILE_APPEND);
						return array_slice($segments, 1);
					}
					else
					{
						$this->located = -1;
						file_put_contents($_dbg_log, "  located=-1 (file not found)\n", FILE_APPEND);
					}
				}

				/* module controller exists? */
				if(is_file($source.ucfirst($module).$ext))
				{
					$this->located = 1;
					file_put_contents($_dbg_log, "  located=1, return ".implode('/',$segments)."\n", FILE_APPEND);
					return $segments;
				}
			}
		}

		file_put_contents($_dbg_log, "  after module loop: directory={$this->directory}\n", FILE_APPEND);
		if( ! empty($this->directory)) return;

		/* application sub-directory controller exists? */
		if($directory)
		{
			if(is_file(APPPATH.'controllers/'.$module.'/'.ucfirst($directory).$ext))
			{
				$this->directory = $module.'/';
				return array_slice($segments, 1);
			}

			/* application sub-sub-directory controller exists? */
			if($controller)
			{
				if(is_file(APPPATH.'controllers/'.$module.'/'.$directory.'/'.ucfirst($controller).$ext))
				{
					$this->directory = $module.'/'.$directory.'/';
					return array_slice($segments, 2);
				}
			}
		}

		/* application controllers sub-directory exists? */
		if (is_dir(APPPATH.'controllers/'.$module.'/'))
		{
			$this->directory = $module.'/';
			return array_slice($segments, 1);
		}

		/* application controller exists? */
		if (is_file(APPPATH.'controllers/'.ucfirst($module).$ext))
		{
			return $segments;
		}

		$this->located = -1;
		file_put_contents($_dbg_log, "  located=-1 (fallthrough)\n", FILE_APPEND);
	}

	/* set module path */
	protected function _set_module_path(&$_route)
	{
		if ( ! empty($_route))
		{
			// Are module/directory/controller/method segments being specified?
			$sgs = sscanf($_route, '%[^/]/%[^/]/%[^/]/%s', $module, $directory, $class, $method);
			
			// set the module/controller directory location if found
			if ($this->locate(array($module, $directory, $class)))
			{
				//reset to class/method
				switch ($sgs)
				{
					case 1:	$_route = $module.'/index';
						break;
					case 2: $_route = ($this->located < 2) ? $module.'/'.$directory : $directory.'/index';
						break;
					case 3: $_route = ($this->located == 2) ? $directory.'/'.$class : $class.'/index';
						break;
					case 4: $_route = ($this->located == 3) ? $class.'/'.$method : $method.'/index';
						break;
				}
			}
		}
	}

	public function set_class($class)
	{
// 		$suffix = $this->config->item('controller_suffix');
// 		if (strpos($class, $suffix) === FALSE)
// 		{
// 			$class .= $suffix;
// 		}

        $suffix = (string) $this->config->item('controller_suffix');
        if ($suffix && strpos($class, $suffix) === FALSE) {
            $class .= $suffix;
        }
		parent::set_class($class);
	}
}	