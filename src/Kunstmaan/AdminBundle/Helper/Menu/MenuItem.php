<?php

namespace Kunstmaan\AdminBundle\Helper\Menu;

/**
 * MenuItem
 */
class MenuItem
{
    /* @var MenuBuilder $menu */
    private $menu;

    /* @var string $internalName */
    private $internalName;

    /* @var string $role */
    private $role;

    /* @var MenuItem $parent */
    private $parent;

    /* @var string $route */
    private $route;

    /* @var array $routeParams */
    private $routeParams = array();

    /* @var boolean $active */
    private $active = false;

    /* @var MenuItem[] $children */
    private $children = null;

    /* @var array $attributes */
    private $attributes = array();

    /* @var boolean $appearInNavigation */
    private $appearInNavigation = true;

    /* @var int $weight */
    private $weight = -50;

    /**
     * Constructor
     *
     * @param MenuBuilder $menu
     */
    public function __construct(MenuBuilder $menu)
    {
        $this->menu = $menu;
    }

    /**
     * Get menu builder
     *
     * @return MenuBuilder
     */
    public function getMenu()
    {
        return $this->menu;
    }

    /**
     * Get internal name
     *
     * @return string
     */
    public function getInternalName()
    {
        return $this->internalName;
    }

    /**
     * Set internal name
     *
     * @param string $internalName
     *
     * @return MenuItem
     */
    public function setInternalName($internalName)
    {
        $this->internalName = $internalName;

        return $this;
    }

    /**
     * Get role
     *
     * @return string
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * Set role
     *
     * @param string $role
     *
     * @return MenuItem
     */
    public function setRole($role)
    {
        $this->role = $role;

        return $this;
    }

    /**
     * Get parent menu item
     *
     * @return MenuItem|null
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Set parent menu item
     *
     * @param MenuItem $parent
     *
     * @return MenuItem
     */
    public function setParent(MenuItem $parent = null)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Get route for menu item
     *
     * @return string
     */
    public function getRoute()
    {
        return $this->route;
    }

    /**
     * Set route and parameters for menu item
     *
     * @param string $route  The route
     * @param array  $params The route parameters
     *
     * @return MenuItem
     */
    public function setRoute($route, array $params = array())
    {
        $this->route       = $route;
        $this->routeParams = $params;

        return $this;
    }

    /**
     * Get route parameters for menu item
     *
     * @return array
     */
    public function getRouteParams()
    {
        return $this->routeParams;
    }

    /**
     * Set route parameters
     *
     * @param array $routeParams
     *
     * @return MenuItem
     */
    public function setRouteParams(array $routeParams = array())
    {
        $this->routeParams = $routeParams;

        return $this;
    }

    /**
     * Get children of current menu item
     *
     * @return MenuItem[]
     */
    public function getChildren()
    {
        if (is_null($this->children)) {
            $this->children = $this->menu->getChildren($this);
        }

        return $this->children;
    }

    /**
     * Get children of current menu item that have the appearInNavigation flag set
     *
     * @return MenuItem[]
     */
    public function getNavigationChildren()
    {
        $result   = array();
        $children = $this->getChildren();
        foreach ($children as $child) {
            if ($child->getAppearInNavigation()) {
                $result[] = $child;
            }
        }

        return $result;
    }

    /**
     * Return top children of current menu item
     *
     * @return TopMenuItem[]
     */
    public function getTopChildren()
    {
        $result   = array();
        $children = $this->getChildren();
        foreach ($children as $child) {
            if ($child instanceof TopMenuItem) {
                $result[] = $child;
            }
        }

        return $result;
    }

    /**
     * Add attributes
     *
     * @param array $attributes
     *
     * @return MenuItem
     */
    public function addAttributes($attributes)
    {
        $this->attributes = array_merge($this->attributes, $attributes);

        return $this;
    }

    /**
     * Get attributes
     *
     * @return array
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    /**
     * Get menu item active state
     *
     * @return bool
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * Set menu item active state
     *
     * @param bool $active
     *
     * @return MenuItem
     */
    public function setActive($active)
    {
        $this->active = $active;

        return $this;
    }

    /**
     * Get appearInNavigation flag
     *
     * @return bool
     */
    public function getAppearInNavigation()
    {
        return $this->appearInNavigation;
    }

    /**
     * Set appearInNavigation flag
     *
     * @param bool $appearInNavigation
     *
     * @return MenuItem
     */
    public function setAppearInNavigation($appearInNavigation)
    {
        $this->appearInNavigation = $appearInNavigation;

        return $this;
    }

    /**
     * Get weight
     *
     * @return int
     */
    public function getWeight()
    {
        return $this->weight;
    }

    /**
     * Set weight
     *
     * @param int $weight
     *
     * @return MenuItem
     */
    public function setWeight($weight)
    {
        $this->weight = $weight;

        return $this;
    }

}