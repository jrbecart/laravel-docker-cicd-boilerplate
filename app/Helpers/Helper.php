<?php 
  
  /**
   * @codeCoverageIgnore
   */
  function docker_secret(string $name)
  {
      return trim(file_get_contents('/run/secrets/' . $name));
  }