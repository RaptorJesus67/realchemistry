<?php

	class imageCentering {
		
		
		// MAIN CENTERING FUNCTION
		public function centerImage($source, $maxHeight, $maxWidth) {
			
			// Make variable local
			$this->source = $source;
			$this->srcHeight = $maxHeight;
			$this->srcWidth = $maxWidth;
			
			// Get image dimensions
			$this->imageSize($this->source);
			
			// Make other local variables
			$this->imgHeight = $this->img["height"];
			$this->imgWidth = $this->img["width"];
			
			
			// Create size terms
			$this->fullLarger = (($this->imgHeight > $this->srcHeight) && ($this->imgWidth > $this->srcHeight)) ? true : false;
			$this->heightLarger = (($this->imgHeight > $this->srcHeight) && ($this->imgWidth < $this->srcHeight)) ? true : false;
			$this->widthLarger = (($this->imgHeight < $this->srcHeight) && ($this->imgWidth > $this->srcHeight)) ? true : false;
			$this->equal = (($this->imgHeight == $this->srcHeight) && ($this->imgWidth == $this->srcHeight)) ? true : false;
			$this->smaller = (($this->imgHeight < $this->srcHeight) && ($this->imgWidth < $this->srcHeight)) ? true : false;
			
			
			
			// SIZE CHECKS
			
			// Image is larger in width and height
			if ($this->fullLarger) {
				
				return $this->imageDiffDimensionThanSource();
				
			// Height is larger, width is smaller
			} elseif($this->heightLarger) {
				
				return $this->heightLargerThanSource();
				
			// Width is larger, height is smaller
			} elseif ($this->widthLarger) {
				
				return $this->widthLargerThanSource();
				
			// Image is equal
			} elseif ($this->equal) {
				
				return $this->equalToSource();
				
			} elseif ($this->smaller) {
				
				return $this->imageDiffDimensionThanSource();
				
			}
			
		}
		
		
		
		// RETURN HEIGHT AND IMAGE WIDTH
		private function imageSize($source) {
			
			list($this->img["width"], $this->img["height"]) = getimagesize($source);
			
			return $this->img;
			
		}
		
		
		
		// PROCESS IF IMAGE IS HAS DIFFERENT DIMENSION THAN SOURCE
		private function imageDiffDimensionThanSource() {
			
			// IMAGE IS LARGER THAN SOURCE PARAMETERS
			
			// Is height is larger than width
			if ($this->imgWidth < $this->imgHeight) {
				
				$ratio = $this->srcHeight / $this->imgHeight;
				$width = $ratio * $this->imgWidth;
				$margin = (($this->srcWidth / 2) - ($width / 2));
				
				
				$this->image["height"] = $this->srcHeight;
				$this->image["width"] = $width;
				$this->image["margin"] = "0 0 0 " . $margin . "px";
				
				return $this->image;
				
				
				
			// Is width larger than height
			} elseif ($this->imgWidth > $this->imgHeight) {
				
				$ratio = $this->srcWidth / $this->imgWidth;
				$height = $ratio * $this->imgHeight;
				$height = ($height < $this->srcHeight) ? $this->srcHeight : $height;
				$margin = (($this->srcHeight / 2) - ($height / 2));
				
				$this->image["width"] = $this->srcWidth;
				$this->image["height"] = $height;
				$this->image["margin"] = $margin . "px 0 0 0";
			
				return $this->image;
				
				
			
			// The Image height and width are the same size
			} else if ($this->imgWidth == $this->imgHeight) {
				
				
				$this->image["height"] = $this->srcHeight;
				$this->image["width"] = $this->srcWidth;
				$this->image["margin"] = 0;
					
				
				return $this->image;
				
				
				
			// Did you screw up
			} else {
				
				echo "There seems to be an error";
				
			}
		}
		
		
		
		// IMAGE HEIGHT IS LARGER THAN THE SOURCE
		private function heightLargerThanSource() {
			
			// Only one function since width can't be bigger than source like height, so it's smaller
			// It can't be equal or else it can't be height larger than source.
			$ratio = $this->srcWidth / $this->imgWidth;
			$height = $ratio * $this->imgHeight;
			$margin = (($this->srcHeight / 2) - ($height / 2));
			
			$this->image["height"] = $height;
			$this->image["width"] = $this->srcWidth;
			$this->image["margin"] = $margin . "px 0 0 0";
			
			return $this->image;
			
		}
		
		
		
		// IMAGE HEIGHT IS LARGER THAN THE SOURCE
		private function widthLargerThanSource() {
			
			// Only one function since height can't be bigger than source like width, so it's smaller
			// It can't be equal or else it can't be width larger than source.
			$ratio = $this->srcHeight / $this->imgHeight;
			$width = $ratio * $this->imgWidth;
			$margin = (($this->srcWidth / 2) - ($width / 2));
			
			$this->image["width"] = $width;
			$this->image["height"] = $this->srcHeight;
			$this->image["margin"] = "0 0 0 " . $margin . "px";
			
			return $this->image;
			
		}
		
		
		
		
		// IMAGE IS EQUAL IN BOTH PARAMETERS AND SIZE TO ORIGINAL
		private function equalToSource() {
			
			$this->image["width"] = $this->srcWidth;
			$this->image["height"] = $this->srcHeight;
			$this->image["margin"] = 0;
			
			return $this->image;
			
		}
		
		
		
		
	
	}

?>