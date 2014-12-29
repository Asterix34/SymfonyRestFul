<?php

namespace Acme\BlogBundle\Model;

Interface AuthorInterface
{
    /**
     * Set title
     *
     * @param string $name
     * @return AuthorInterface
     */
    public function setName($name);

    /**
     * Get title
     *
     * @return string 
     */
    public function getName();

    /**
     * Set body
     *
     * @param string $login
     * @return AuthorInterface
     */
    public function setLogin($login);

    /**
     * Get body
     *
     * @return string 
     */
    public function getLogin();
    
    /**
     * Set password
     *
     * @param string $password
     * @return AuthorInterface
     */
    public function setPassword($password);
    
    /**
     * Get password
     *
     * @return string
    */
    public function getPassword();
}
