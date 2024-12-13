<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class PagesController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        return $this->render('pages/index.html.twig');
    }

    #[Route('/about', name: 'page_about')]
    public function about(): Response
    {
        return $this->render('pages/about.html.twig');
    }

    #[Route('/courses', name: 'page_courses')]
    public function courses(): Response
    {
        return $this->render('pages/courses.html.twig');
    }

    #[Route('/events', name: 'page_events')]
    public function events(): Response
    {
        return $this->render('pages/events.html.twig');
    }
    
    #[Route('/Tache', name: 'page_Tache')]
    public function Tache(): Response
    {
        return $this->render('pages/Tache.html.twig');
    }

    #[Route('/contact', name: 'page_contact')]
    public function contact(): Response
    {
        return $this->render('pages/contact.html.twig');
    }

    #[Route('/teacher', name: 'page_teacher')]
    public function teacher(): Response
    {
        return $this->render('pages/teacher.html.twig');
    }

    #[Route('/teacher-single', name: 'page_teacher_single')]
    public function teacher_single(): Response
    {
        return $this->render('pages/teacher-single.html.twig');
    }


    #[Route('/notice', name: 'page_notice')]
    public function notice(): Response
    {
        return $this->render('pages/notice.html.twig');
    }
    #[Route('/notice_single', name: 'page_notice_single')]
    public function notice_single(): Response
    {
        return $this->render('pages/notice-single.html.twig');
    }


    #[Route('/research', name: 'page_research')]
    public function research(): Response
    {
        return $this->render('pages/research.html.twig');
    }


    #[Route('/scholarship', name: 'page_scholarship')]
    public function scholarship(): Response
    {
        return $this->render('pages/scholarship.html.twig');
    }


    #[Route('/course-single', name: 'page_course-single')]
    public function course_single(): Response
    {
        return $this->render('pages/course-single.html.twig');
    }

    #[Route('/event-single', name: 'page_event-single')]
    public function event_single(): Response
    {
        return $this->render('pages/event-single.html.twig');
    }
    

    #[Route('/Tache-single', name: 'page_Tache-single')]
    public function Tache_single(): Response
    {
        return $this->render('pages/Tache-single.html.twig');
    }

}
