<?php namespace MapBundle\Document;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Doctrine\Bundle\MongoDBBundle\Validator\Constraints\Unique as MongoDBUnique;
use Symfony\Component\Security\Core\Role\Role;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation as Serializer;

/**
 * @MongoDB\Document
 * @MongoDBUnique(fields="email")
 * @MongoDBUnique(fields="username")
 */
class User {

    /**
     * @MongoDB\Id(strategy="auto")
     */
    protected $id;

    /**
     * @MongoDB\String
     * @Assert\NotBlank
     * @Assert\Length(min=6,max=32)
     */
    protected $username;

    /**
     * @MongoDB\String
     * @Assert\NotBlank
     * @Assert\Length(min=6)
     * @Serializer\Exclude
     */
    protected $password;

    /**
     * @MongoDB\String
     * @Assert\Email
     * @Assert\NotBlank
     */
    protected $email;

    /**
     * @MongoDB\String
     * @Assert\Length(max=1024)
     */
    protected $aboutMe;

    /**
     * @MongoDB\String
     * @Assert\Length(max=64)
     */
    protected $skypeId;

    /**
     * @MongoDB\String
     * @Assert\Length(max=64)
     */
    protected $country;

    /**
     * @MongoDB\String
     * @Assert\Length(max=64)
     */
    protected $city;

    /**
     * @MongoDB\Boolean
     */
    protected $isAdmin = true;

    /**
     * @MongoDB\String
     * @Assert\Url
     */
    protected $website;

    /**
     * @MongoDB\Float
     */
    protected $lat;

    /**
     * @MongoDB\Float
     */
    protected $lng;

    /**
     * @MongoDB\String
     */
    protected $organization = 'tmp';

    /**
     * @MongoDB\Collection
     * @MongoDB\ReferenceMany(targetDocument="MapBundle\Document\Team", mappedBy="users")
     */
    protected $teams;


    public function __construct() {
        $this->teams = new ArrayCollection();
    }

    /**
     * Get id
     *
     * @return id $id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set username
     *
     * @param string $username
     * @return self
     */
    public function setUsername($username)
    {
        $this->username = $username;
        return $this;
    }

    /**
     * Get username
     *
     * @return string $username
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set password
     *
     * @param string $password
     * @return self
     */
    public function setPassword($password)
    {
        $this->password = $password;
        return $this;
    }

    /**
     * Get password
     *
     * @return string $password
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return self
     */
    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    /**
     * Get email
     *
     * @return string $email
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set aboutMe
     *
     * @param string $aboutMe
     * @return self
     */
    public function setAboutMe($aboutMe)
    {
        $this->aboutMe = $aboutMe;
        return $this;
    }

    /**
     * Get aboutMe
     *
     * @return string $aboutMe
     */
    public function getAboutMe()
    {
        return $this->aboutMe;
    }

    /**
     * Set skypeId
     *
     * @param string $skypeId
     * @return self
     */
    public function setSkypeId($skypeId)
    {
        $this->skypeId = $skypeId;
        return $this;
    }

    /**
     * Get skypeId
     *
     * @return string $skypeId
     */
    public function getSkypeId()
    {
        return $this->skypeId;
    }

    /**
     * Set country
     *
     * @param string $country
     * @return self
     */
    public function setCountry($country)
    {
        $this->country = $country;
        return $this;
    }

    /**
     * Get country
     *
     * @return string $country
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Set city
     *
     * @param string $city
     * @return self
     */
    public function setCity($city)
    {
        $this->city = $city;
        return $this;
    }

    /**
     * Get city
     *
     * @return string $city
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set isAdmin
     *
     * @param string $isAdmin
     * @return self
     */
    public function setIsAdmin($isAdmin)
    {
        $this->isAdmin = $isAdmin;
        return $this;
    }

    /**
     * Get isAdmin
     *
     * @return string $isAdmin
     */
    public function getIsAdmin()
    {
        return $this->isAdmin;
    }

    /**
     * Set website
     *
     * @param string $website
     * @return self
     */
    public function setWebsite($website)
    {
        $this->website = $website;
        return $this;
    }

    /**
     * Get website
     *
     * @return string $website
     */
    public function getWebsite()
    {
        return $this->website;
    }

    /**
     * Set organization
     *
     * @param string $organization
     * @return self
     */
    public function setOrganization($organization)
    {
        $this->organization = $organization;
        return $this;
    }

    /**
     * Get organization
     *
     * @return string $organization
     */
    public function getOrganization()
    {
        return $this->organization;
    }

    public function getRoles()
    {
        if ($this->getIsAdmin()) {
            return (empty($this->getOrganization()) ? ['ROLE_ADMIN', 'ROLE_USER']: ['ROLE_ORGANIZATION_ADMIN', 'ROLE_USER']);
        } else {
            return ['ROLE_USER'];
        }
    }

    /**
     * Set teams
     *
     * @param collection $teams
     * @return self
     */
    public function setTeams($teams)
    {
        $this->teams = $teams;
        return $this;
    }

    /**
     * Get teams
     *
     * @return collection $teams
     */
    public function getTeams()
    {
        return $this->teams;
    }

    public function addTeam(Team $team) {
        $this->teams[] = $team;
    }

    public function removeTeam(Team $team) {
        $this->teams->removeElement($team);
    }

    /**
     * Set lat
     *
     * @param float $lat
     * @return self
     */
    public function setLat($lat)
    {
        $this->lat = $lat;
        return $this;
    }

    /**
     * Get lat
     *
     * @return float $lat
     */
    public function getLat()
    {
        return $this->lat;
    }

    /**
     * Set lng
     *
     * @param float $lng
     * @return self
     */
    public function setLng($lng)
    {
        $this->lng = $lng;
        return $this;
    }

    /**
     * Get lng
     *
     * @return float $lng
     */
    public function getLng()
    {
        return $this->lng;
    }
}