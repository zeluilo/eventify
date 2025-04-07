<?php include '../includes/error-message.php'; ?>

<section id="home" class="home section dark-background">

  <!-- <img src="assets/img/hero-bg.jpg" alt="Hero Background"> -->

  <div class="home-content">
    <h2>Welcome to Eventify</h2>
    <p>Your go-to platform for managing and showcasing exciting community events.</p>
    <a href="#events" class="btn-get-started">Search</a>
  </div>

</section>

<section id="about" class="about-section">

  <div class="about-title-container" data-aos="fade-up">
    <h2>About Eventify</h2>
    <p>
      Eventify is a dynamic web-based event management system designed to streamline the organization, promotion, and administration of community and business events.
    </p>
  </div>

  <div class="about-content-container" data-aos="fade-up" data-aos-delay="100">

    <div class="about-row">
      <div class="about-image">
        <img src="/assets/images/eventify.png" alt="About Eventify Image">
      </div>
      <div class="about-text">
        <h3>Streamlined Event Management Platform</h3>
        <p class="highlighted-text">
          Our platform offers a user-friendly experience for both event organizers and attendees, ensuring seamless event discovery, registration, and management.
        </p>
        <ul>
          <li><span class="icon">✔</span> Admins can create, update, and delete events with ease.</li>
          <li><span class="icon">✔</span> Public users can search and filter events by type, date, or location.</li>
          <li><span class="icon">✔</span> Built with responsive design and modern technologies for mobile and desktop accessibility.</li>
        </ul>
        <p>
          Eventify provides two core interfaces: an Admin Interface for event control and a Public Interface for discovering available events. It is ideal for small to medium-sized organizations seeking a simple yet powerful event management solution.
        </p>
      </div>
    </div>

  </div>

</section>

<section id="events" class="events section">
  <div class="section-title-wrapper" data-aos="fade-up">
    <h2>Available Events</h2>
    <p>Stay updated with our latest community happenings and gatherings</p>
    <a class="view-btn" href="/events/view">View All Events</a>
  </div>

  <div class="events-main">
    
    <!-- Upcoming Events -->
    <?php if (!empty($upcomingEvents)): ?>
      <div class="events-section">
        <h3 class="events-title">Upcoming Events</h3>
        <div class="events-cards-wrapper">
          <?php foreach ($upcomingEvents as $event): ?>
            <div class="event-card" data-aos="fade-up" data-aos-delay="100">
              <div class="event-item">
                <h3><?= htmlspecialchars($event['title']) ?></h3>
                <p class="event-date"><?= htmlspecialchars(date('F j, Y, g:i a', strtotime($event['event_date']))) ?></p>
                <p class="event-location"><?= htmlspecialchars($event['location']) ?></p>
                <div class="btn-wrap">
                  <a class="btn-view" href="/events/view?eventId=<?= $event['eventId'] ?>">View Event</a>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    <?php endif; ?>

    <!-- Ongoing Events -->
    <?php if (!empty($ongoingEvents)): ?>
      <div class="events-section">
        <h3 class="events-title">Ongoing Events</h3>
        <div class="events-cards-wrapper">
          <?php foreach ($ongoingEvents as $event): ?>
            <div class="event-card" data-aos="fade-up" data-aos-delay="100">
              <div class="event-item">
                <h3><?= htmlspecialchars($event['title']) ?></h3>
                <p class="event-date"><?= htmlspecialchars(date('F j, Y, g:i a', strtotime($event['event_date']))) ?></p>
                <p class="event-location"><?= htmlspecialchars($event['location']) ?></p>
                <div class="btn-wrap">
                  <a class="btn-view" href="/events/view?eventId=<?= $event['eventId'] ?>">View Event</a>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    <?php endif; ?>

    <!-- Past Events -->
    <?php if (!empty($pastEvents)): ?>
      <div class="events-section">
        <h3 class="events-title">Past Events</h3>
        <div class="events-cards-wrapper">
          <?php foreach ($pastEvents as $event): ?>
            <div class="event-card" data-aos="fade-up" data-aos-delay="100">
              <div class="event-item">
                <h3><?= htmlspecialchars($event['title']) ?></h3>
                <p class="event-date"><?= htmlspecialchars(date('F j, Y, g:i a', strtotime($event['event_date']))) ?></p>
                <p class="event-location"><?= htmlspecialchars($event['location']) ?></p>
                <div class="btn-wrap">
                  <a class="btn-view" href="/events/view?eventId=<?= $event['eventId'] ?>">View Event</a>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    <?php endif; ?>

    <!-- If No Events Exist -->
    <?php if (empty($upcomingEvents) && empty($ongoingEvents) && empty($pastEvents)): ?>
      <p class="no-events">No current events available.</p>
    <?php endif; ?>

  </div>
</section>
