<?

/*
	ImageMagick class 1.0
	written by: daniel@bokko.nl

	(c)1999 - 2003 All copyrights by: Dani�l Eiland

	This library is free software; you can redistribute it and/or
	modify it under the terms of the GNU Lesser General Public
	License as published by the Free Software Foundation; either
	version 2.1 of the License, or (at your option) any later version.

	This library is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
	Lesser General Public License for more details.
	(http://www.gnu.org/licenses/lgpl.txt)
*/

	class ImageMagick_noUpload {

		var $targetdir      = '';
		var $imagemagickdir = '/usr/bin';
		var $temp_dir		= 'temp'; // httpd must be able to write there
		var $file_history   = array();
		var $temp_file      = '';
		var $jpg_quality	= '75';
		var $count			= 0;
		var $image_data     = array();
		var $error          = '';
		var $verbose        = false;

	
				
	

			/*
			 	Constructor places uploaded file in $this->temp_dir
			 	Gets the imagedata and stores it in $this->image_data
			 	$filedata = $_FILES['file1']
			*/


			
			function ImageMagick_noUpload($filename) {				
				
				
				//$this->temp_file = ereg_replace("[^a-zA-Z0-9_.]", '_', $filedata['name']);
				$this->temp_file = substr($filename,strrpos($filename,"/")+1);
				
				$copyTo = $this->temp_dir.'/tmp'.$this->count.'_'.$this->temp_file;
				if(!copy($filename,$copyTo)){
				
					$errors["image"] = "Imagemagick: Upload failed<BR>";
					if($this->verbose == TRUE) {
						echo "filename: $filename <BR>";
						echo "temp_file : ".$this->temp_file." <BR>";
						echo "Could Not Copy \"$filename\" to \"$copyTo\" <BR>";
					}					
				}
			
					
				$this->file_history[] = $this->temp_dir.'/tmp'.$this->count.'_'.$this->temp_file;
				$this->GetSize();
			}

			function returnXsize($filedata) {
				$this->size = $this->GetSize();
				$xsize = $this->size[0];
				return $xsize;
			}
			function returnYsize($filedata) {
				$this->size = $this->GetSize();
				$ysize = $this->size[1];
				return $ysize;
			}

			/*

				setTargetdir(string string)
				Sets the dir to where the images are saved
				httpd user must have write access there
			*/

			function setTargetdir($target) {
				if($target == '')
					$this->targetdir = $this->temp_dir;
				else
					$this->targetdir = $target;
				if($this->verbose == TRUE) {
					echo "Set target dir to '{$this->targetdir}'\n";
				}
			}




			/*
				string getFilename()
				Returns the current filename
			*/

			function getFilename() {
				return $this->temp_file;
				
			}




			/*
				setVerbose(bool)
				if set to TRUE, all information is displayed
			*/

			function setVerbose($v=FALSE) {
				$this->verbose = $v;
				if($v == TRUE) {
					echo '<pre>';
				}
			}




			/*
				array GetSize()
				returns the size of the image in an array
				array[0] = x-size
				array[1] = y-size
			*/

			function GetSize() {
				$command = $this->imagemagickdir."/identify -verbose '".$this->temp_dir.'/tmp'.$this->count.'_'.$this->temp_file."'";
				exec($command, $returnarray, $returnvalue);
				if($returnvalue){
					$errors["image"] = "Imagemagick: Corrupt image";
					//die("ImageMagick: Corrupt image");
				}
				else {
					$num = count($returnarray);
					for($i=0;$i<$num;$i++)
						$returnarray[$i] = trim($returnarray[$i]);
					$this->image_data = $returnarray;
				}
				$num = count($this->image_data);
				for($i=0;$i<$num;$i++)
					if(eregi('Geometry', $this->image_data[$i])) {
						$tmp1 = explode(' ', $this->image_data[$i]);
						$tmp2 = explode('x', $tmp1[1]);
						$this->size = $tmp2;
						return $tmp2;
					}
			}




			/*
				Flip(string string)
				flips the image
				possible arguments:
					'horizontal' > flips the image horizontaly
					'vertical' > flips the image verticaly
				default is horizontal
			*/

			function Flip($var='horizontal') {

				if($this->verbose == TRUE) {
					echo "Flip:\n";
				}
				$tmp = $var=='horizontal'?'-flop':($var=='vertical'?'-flip':'');
				if($this->verbose == TRUE) {
					echo "  Method: {$var}\n";
				}
				$command = "{$this->imagemagickdir}/convert {$tmp} '{$this->temp_dir}/tmp{$this->count}_{$this->temp_file}' '{$this->temp_dir}/tmp".++$this->count."_{$this->temp_file}'";
				if($this->verbose == TRUE) {
					echo "  Command: {$command}\n";
				}
				exec($command, $returnarray, $returnvalue);
				if($returnvalue) {
					$this->error .= "ImageMagick: Flip failed\n";
					if($this->verbose == TRUE) {
						echo "Flip failed\n";
					}
				} else {
					$this->file_history[] = $this->temp_dir.'/tmp'.$this->count.'_'.$this->temp_file;
				}
			}




			/*
				Dithers the image
			*/

			function Dither() {

				if($this->verbose == TRUE) {
					echo "Dither:\n";
				}
				$command = "{$this->imagemagickdir}/convert -dither '{$this->temp_dir}/tmp{$this->count}_{$this->temp_file}' '{$this->temp_dir}/tmp".++$this->count."_{$this->temp_file}'";
				if($this->verbose == TRUE) {
					echo "  Command: {$command}\n";
				}
				exec($command, $returnarray, $returnvalue);
				if($returnvalue) {
					$this->error .= "ImageMagick: Dither failed\n";
					if($this->verbose == TRUE) {
						echo "Dither failed\n";
					}
				} else {
					$this->file_history[] = $this->temp_dir.'/tmp'.$this->count.'_'.$this->temp_file;
				}
			}




			/*
				Converts the image to monochrome (2 color black-white)
			*/

			function Monochrome() {

				if($this->verbose == TRUE) {
					echo "Monochrome:\n";
				}
				$command = "{$this->imagemagickdir}/convert -monochrome '{$this->temp_dir}/tmp{$this->count}_{$this->temp_file}' '{$this->temp_dir}/tmp".++$this->count."_{$this->temp_file}'";
				if($this->verbose == TRUE) {
					echo "  Command: {$command}\n";
				}
				exec($command, $returnarray, $returnvalue);
				if($returnvalue) {
					$this->error .= "$ImageMagick: Monochrome failed\n";
					if($this->verbose == TRUE) {
						echo "Monochrome failed\n";
					}
				} else {
					$this->file_history[] = $this->temp_dir.'/tmp'.$this->count.'_'.$this->temp_file;
				}
			}
			
			
			function Greyscale() {

			if($this->verbose == TRUE) {
					echo "Greyscale:\n";
				}
				$command = "{$this->imagemagickdir}/convert -greyscale '{$this->temp_dir}/tmp{$this->count}_{$this->temp_file}' '{$this->temp_dir}/tmp".++$this->count."_{$this->temp_file}'";
				if($this->verbose == TRUE) {
					echo "  Command: {$command}\n";
				}
				exec($command, $returnarray, $returnvalue);
				if($returnvalue) {
					$this->error .= "$ImageMagick: Greyscale failed\n";
					if($this->verbose == TRUE) {
						echo "Greyscale failed\n";
					}
				} else {
					$this->file_history[] = $this->temp_dir.'/tmp'.$this->count.'_'.$this->temp_file;
				}
			}




			/*
				Converts the image to it's negative
			*/

			function Negative() {

				if($this->verbose == TRUE) {
					echo "Negative:\n";
				}
				$command = "{$this->imagemagickdir}/convert -negate '{$this->temp_dir}/tmp{$this->count}_{$this->temp_file}' '{$this->temp_dir}/tmp".++$this->count."_{$this->temp_file}'";
				if($this->verbose == TRUE) {
					echo "  Command: {$command}\n";
				}
				exec($command, $returnarray, $returnvalue);
				if($returnvalue) {
					$this->error .= "ImageMagick: Negative failed\n";
					if($this->verbose == TRUE) {
						echo "Negative failed\n";
					}
				} else {
					$this->file_history[] = $this->temp_dir.'/tmp'.$this->count.'_'.$this->temp_file;
				}
			}




			/*
				Rotate(float value, string string, string string)
				Rotates the image
				possible values for arg1:
					numbers from 0-360
				possible values for arg2:
					hexadecimal color without the "#" for example: C3D6A0
				possible values for arg3:
					no value > standard rotation
					'morewidth' > rotates the image only if only if its width exceeds the height
					'lesswidth' > rotates the image only if its width is less than the height
			*/

			function Rotate($deg=90, $bgcolor='000000', $how='') {

				$tmp = $how=='lesswidth'?"<":($how=='morewidth'?">":'');
				if($this->verbose == TRUE) {
					echo "Rotate:\n";
					echo "  Degrees: {$deg}\n";
					echo "  Method: ".($how==''?'standard':$how)."\n";
					echo "  Background color: #{$bgcolor}\n";
				}
				$command = "{$this->imagemagickdir}/convert -background '#{$bgcolor}' -rotate '{$deg}{$tmp}' '{$this->temp_dir}/tmp{$this->count}_{$this->temp_file}' '{$this->temp_dir}/tmp".++$this->count."_{$this->temp_file}'";
				if($this->verbose == TRUE) {
					echo "  Command: {$command}\n";
				}
				exec($command, $returnarray, $returnvalue);
				if($returnvalue) {
					$this->error .= "ImageMagick: Rotate failed\n";
					if($this->verbose == TRUE) {
						echo "Rotate failed\n";
					}
				} else {
					$this->file_history[] = $this->temp_dir.'/tmp'.$this->count.'_'.$this->temp_file;
				}
			}




			/*
				Blur(int value, int value)
				blur the image with a gaussian operator
				arg1 > radius
				arg2 > sigma
			*/

			function Blur($radius=5, $sigma=2) {

				if($this->verbose == TRUE) {
					echo "Blur:\n";
					echo "  Radius: {$radius}\n";
					echo "  Sigma: {$sigma}\n";
				}
				$command = "{$this->imagemagickdir}/convert -blur '{$radius}x{$sigma}' '{$this->temp_dir}/tmp{$this->count}_{$this->temp_file}' '{$this->temp_dir}/tmp".++$this->count."_{$this->temp_file}'";
				if($this->verbose == TRUE) {
					echo "  Command: {$command}\n";
				}
				exec($command, $returnarray, $returnvalue);
				if($returnvalue) {
					$this->error .= "ImageMagick: Blur failed\n";
					echo "Blur failed\n";
				} else {
					$this->file_history[] = $this->temp_dir.'/tmp'.$this->count.'_'.$this->temp_file;
				}
			}




			/*
				Frame(int value, sting string)
				Draws a frame around the image
				arg1 > frame width in pixels
				arg2 > frame color in hexadecimal, for exaple: 4AF2C9
			*/

			function Frame($width=3, $color='302E00') {

				if($this->verbose == TRUE) {
					echo "<br><br><b>Frame:</b>\n";
					echo "  Width: {$width}\n";
					echo "  Color: {$color}\n";
				}
				$command = "{$this->imagemagickdir}/convert -mattecolor '#{$color}' -frame '{$width}x{$width}' '{$this->temp_dir}/tmp{$this->count}_{$this->temp_file}' '{$this->temp_dir}/tmp".++$this->count."_{$this->temp_file}'";
				if($this->verbose == TRUE) {
					echo "  Command: {$command}\n";
				}
				exec($command, $returnarray, $returnvalue);
				if($returnvalue) {
					$this->error .= "ImageMagick: Frame failed\n";
					if($this->verbose == TRUE) {
						echo "Frame failed\n";
					}
				} else {
					$this->file_history[] = $this->temp_dir.'/tmp'.$this->count.'_'.$this->temp_file;
				}
			}




			/*
				Resize(int value, int value, string string)
				Resize the image to given size
				possible values:
					arg1 > x-size, unsigned int
					arg2 > y-size, unsigned int
					arg3 > resize method;
								'keep_aspect' > changes only width or height of image
								'fit' > fit image to given size
			*/

			function Resize($x_size, $y_size, $how='keep_aspect') {

				if($this->verbose == TRUE) {
					echo "<br><bR><b>Resize:</b>\n";
				}

				$method = $how=='keep_aspect'?'>':($how=='fit'?'!':'');

				if($this->verbose == TRUE) {
					echo "<br>  Resize method: {$how}\n";
				}

				// use this command to restrict the image to the x and y sizes
				$command = "{$this->imagemagickdir}/convert -geometry '{$x_size}x{$y_size}{$method}' '{$this->temp_dir}/tmp{$this->count}_{$this->temp_file}' '{$this->temp_dir}/tmp".++$this->count."_{$this->temp_file}'";
				
				if($this->verbose == TRUE) {
					echo "<br>  Command: {$command}\n";
					echo "<br>  Args: targetsize_x = {$x_size}\n";
					echo "<br>  Args: targetsize_y = {$y_size}\n";
				}

				exec($command, $returnarray, $returnvalue);

				if($returnvalue) {
					$this->error .= "ImageMagick: Resize failed\n";
					if($this->verbose == TRUE) {
						echo "<br>Resize failed\n";
					}
				} else {
					$this->file_history[] = $this->temp_dir.'/tmp'.$this->count.'_'.$this->temp_file;
				}
			}




			/*
				Square(string string)
				Makes the image a square
				possible arguments:
					'center' > crops to a square in the center of the image
					'left' > crops to a square on the left side of the image
					'right' > crops to a square on the right side of the image
			*/

			function Square($how='center') {

				$this->size = $this->GetSize();
				if($how == 'center') {
					if($this->size[0] > $this->size[1])
						$line = $this->size[1].'x'.$this->size[1].'+'.round((($this->size[0] - $this->size[1]) / 2)).'+0';
					else
						$line = $this->size[0].'x'.$this->size[0].'+0+'.round((($this->size[1] - $this->size[0])) / 2);
				}
				if($how == 'left') {
					if($this->size[0] > $this->size[1])
						$line = $this->size[1].'x'.$this->size[1].'+0+0';
					else
						$line = $this->size[0].'x'.$this->size[0].'+0+0';
				}
				if($how == 'right') {
					if($this->size[0] > $this->size[1])
						$line = $this->size[1].'x'.$this->size[1].'+'.($this->size[0]-$this->size[1]).'+0';
					else
						$line = $this->size[0].'x'.$this->size[0].'+0+'.($this->size[1] - $this->size[0]);
				}

				$command = "{$this->imagemagickdir}/convert -crop '{$line}' '{$this->temp_dir}/tmp{$this->count}_{$this->temp_file}' '{$this->temp_dir}/tmp".++$this->count."_{$this->temp_file}'";

				if($this->verbose == TRUE) {
					echo "Square:\n";
					echo "  Method: {$how}\n";
					echo "  Command: {$command}\n";
				}
				exec($command, $returnarray, $returnvalue);
				if($returnvalue) {
					$this->error .= "ImageMagick: Square failed\n";
					if($this->verbose == TRUE) {
						echo "Square failed\n";
					}
				} else {
					$this->file_history[] = $this->temp_dir.'/tmp'.$this->count.'_'.$this->temp_file;
				}
			}




			/*
				Crop(int value, int value, string string)
				Crops the image to given size
				arg1 > x-size, unsigned int
				arg2 > y-size, unsigned int
				arg3 > method;
						'center', crops the image leaving the center
						'left', crops only from the right side
						'right', crops only from the left side
			*/

			function Crop($size_x, $size_y, $how='center') {

				if($this->verbose == TRUE) {
					echo "<br><br><b>Crop:</b>\n";
				}

				$this->size = $this->GetSize();

				if($size_x > $this->size[0]) {
					$size_x = $this->size[0];
				}

				if($size_y > $this->size[1]) {
					$size_y = $this->size[1];
				}

				if($this->verbose == TRUE) {
					echo "<br>  Args: size_x = {$size_x}\n";
					echo "<br>  Args: size_y = {$size_y}\n";
					echo "<br>  Crop method: {$how}\n";
					echo "<br>  GetSize: size_x = {$this->size[0]}\n";
					echo "<br>  GetSize: size_y = {$this->size[1]}\n";
				}

				if($how == 'center') {
					$line = $size_x.'x'.$size_y.'+'.round( ($this->size[0] - $size_x) / 2 ).'+'.round((($this->size[1] - $size_y) / 2));
				}

				if($how == 'left') {
					$line = $size_x.'x'.$size_y.'+0+0';
				}

				if($how == 'right') {
					$line = $size_x.'x'.$size_y.'+'.($this->size[0] - $size_x).'+0';
				}

				$command = "{$this->imagemagickdir}/convert -crop '{$line}' '{$this->temp_dir}/tmp{$this->count}_{$this->temp_file}' '{$this->temp_dir}/tmp".++$this->count."_{$this->temp_file}'";

				if($this->verbose==1) {
					echo "  Command: {$command}\n";
				}

				exec($command, $returnarray, $returnvalue);

				if($returnvalue) {
					$this->error .= "ImageMagick: Crop failed\n";
					if($this->verbose == TRUE) {
						echo "<br>Crop failed\n";
					}
				} else {
					$this->file_history[] = $this->temp_dir.'/tmp'.$this->count.'_'.$this->temp_file;
				}
		   }




			/*
				Convert(string string)
				Converts the image to any format using the given file extension
				Defaults to jpg
			*/

			function Convert($format='jpg') {
				
				
				if($this->verbose == TRUE) {
					echo "<br><br><b>Convert:</b>\n";
				}

				$name = explode('.' , $this->temp_file);
				$name = "{$name[0]}.{$format}";

				if($this->verbose == TRUE) {
					echo "<br>  Desired format: {$format}\n";
					echo "<br>  Constructed filename: {$name}\n";
				}

				$command = "{$this->imagemagickdir}/convert -colorspace RGB -quality {$this->jpg_quality} '{$this->temp_dir}/tmp{$this->count}_{$this->temp_file}' '{$this->temp_dir}/tmp".++$this->count."_{$name}'";

				if($this->verbose == TRUE) {
					echo "  Command: {$command}\n";
				}

				exec($command, $returnarray, $returnvalue);

				$this->temp_file = $name;

				if($returnvalue) {
					$this->error .= "ImageMagick: Convert failed\n";
					if($this->verbose == TRUE) {
						echo "<br>Convert failed\n";
					}
				} else {
					$this->file_history[] = $this->temp_dir.'/tmp'.$this->count.'_'.$this->temp_file;
				}
			}




			/*
				string string Save(string string)
				Saves the image to the targetdir, returning the filename
				arg1 > set prefix, for example : 'thumb_'
			*/

			function Save($name,$saveToPath) {
				$prefix='';
					
					
				if($this->verbose == TRUE) {
					echo "<br><br><b>Save:</b>\n";
				}

// delete existing file before resaving because chmod for some reason won't let us overwrite an existing file
					$previousfile = $saveToPath.'/'.$name; 

					if(file_exists($previousfile) && !unlink($previousfile)) {
						//$this->error .= "ImageMagick: Removal of previous file '{$previousfile}' failed\n";
						if($this->verbose == TRUE) {
							echo " <br> Removal of previous file '{$previousfile}' failed\n";
						}
					} else {
						if($this->verbose == TRUE) {
							echo "<br>  Deleted previous file: {$previousfile}\n";
						}
					}

					if($this->verbose == TRUE) {
						echo "<BR>NAME = $name<br>";
					}
					
				if(!@copy($this->temp_dir.'/tmp'.$this->count.'_'.$this->temp_file, $saveToPath.'/'.$name)) {
					$this->error .= "ImageMagick: Couldn't save to $saveToPath/'{$name}\n";
					if($this->verbose == TRUE) {
						echo "<br>Save <b>failed</b> to $saveToPath/{$name}\n";
					}
				} else {
					if($this->verbose == TRUE) {
						echo "<br>Saved to $saveToPath/{$name}\n";
					}
				}
				return $name;
			}




			/*
				Cleans up all the temp data in $this->tempdir
			*/

			function Cleanup() {

				if($this->verbose == TRUE) {
					echo "<br><br><b>Cleanup:</b>\n";
				}

				$num = count($this->file_history);

				for($i=0;$i<$num;$i++) {
					if (is_file($this->file_history[$i])){
						if(!unlink($this->file_history[$i])) {
							$this->error .= "ImageMagick: Removal of temporary file '{$this->file_history[$i]}' failed\n";
							if($this->verbose == TRUE) {
								echo " <br> Removal of temporary file '{$this->file_history[$i]}' failed\n";
							}
						} else {
							if($this->verbose == TRUE) {
								echo "<br>  Deleted temp file: {$this->file_history[$i]}\n";
							}
						}
					}
				}

				if($this->verbose == TRUE) {
					echo '</pre>';
				}
			}

	}
?>