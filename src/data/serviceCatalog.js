/**
 * Detailed per-category service catalog for the /services/:slug pages.
 * Prices are sourced from the clinic's real service price list (top sellers
 * per category) so what's shown here matches what's actually charged.
 */
export const serviceCatalog = {
  wellness: {
    title: 'Wellness & Prevention',
    icon: 'heart',
    tagline: 'Keep small issues from becoming big ones',
    description: 'Regular wellness care catches problems early and keeps your pet protected year-round — from routine exams to permanent ID and travel paperwork.',
    items: [
      { name: 'General Consultation', price: 'KES 2,000', unit: '', blurb: 'A full physical exam and health assessment with one of our vets — the starting point for almost every visit.', popular: true },
      { name: 'Deworming', price: 'KES 250', unit: '/dose', blurb: 'Routine parasite control to keep your pet\'s digestive health on track.' },
      { name: 'Microchipping', price: 'KES 6,500', unit: '', blurb: 'A permanent, scannable ID chip that dramatically improves the odds of a lost pet finding its way home.' },
      { name: 'Local Health Certificate', price: 'KES 2,000', unit: '', blurb: 'Official fitness-to-travel documentation, issued after a vet examination.' },
      { name: 'Local Movement Health Certificate', price: 'KES 1,500', unit: '', blurb: 'Required paperwork for relocating your pet within the country.' },
      { name: 'Fecal & Parasite Screening', price: 'KES 4,500', unit: '/session', blurb: 'Lab screening for internal parasites, worms, and gut health issues before they become symptomatic.' },
    ],
    faqs: [
      { q: 'How often should my pet have a wellness exam?', a: 'We recommend at least once a year for adult pets, and every 6 months for seniors or pets with ongoing conditions.' },
      { q: 'Do I need to book ahead for a general consultation?', a: 'Walk-ins are welcome, but booking online guarantees you a slot and cuts your wait time.' },
    ],
  },

  vaccinations: {
    title: 'Vaccinations',
    icon: 'syringe',
    tagline: 'Protection that keeps pace with your pet\'s life',
    description: 'Core and lifestyle vaccines administered on schedule, tailored to your pet\'s age, environment, and risk exposure.',
    items: [
      { name: 'Rabies Vaccine', price: 'KES 2,000', unit: '', blurb: 'Legally required protection against rabies — renewed annually.', popular: true },
      { name: 'Parvovirus Vaccine', price: 'KES 2,500', unit: '/visit', blurb: 'Targeted protection against canine parvovirus, especially critical for puppies.' },
      { name: 'Canine DHLPP Combo', price: 'KES 3,000', unit: '', blurb: 'Distemper, Hepatitis, Leptospirosis, Parainfluenza & Parvovirus in a single combination vaccine.' },
      { name: 'Feline Cat Flu Combo', price: 'KES 3,000', unit: '', blurb: 'Rhinotracheitis, Calicivirus & Panleukopenia protection for cats.' },
      { name: 'Puppy & Kitten Vaccination Series', price: 'From KES 2,000', unit: '/dose', blurb: 'A full first-year schedule planned with your vet based on age and breed risk.' },
      { name: 'Tetanus Toxoid Vaccination', price: 'KES 1,500', unit: '', blurb: 'Protection against tetanus, commonly recommended after wounds or for at-risk pets.' },
    ],
    faqs: [
      { q: 'When should puppies and kittens start vaccinating?', a: 'Typically from 6–8 weeks old, with boosters every 3–4 weeks until 16 weeks.' },
      { q: 'Can vaccines be given alongside a wellness exam?', a: 'Yes — we always do a quick health check before vaccinating to make sure your pet is fit for it.' },
    ],
  },

  surgery: {
    title: 'Surgery & Orthopaedics',
    icon: 'scalpel',
    tagline: 'Skilled hands, modern equipment, careful aftercare',
    description: 'Soft tissue and orthopaedic procedures performed in our fully equipped surgical suite, with full pre- and post-operative monitoring.',
    items: [
      { name: 'Dog Spay (Ovariohysterectomy)', price: 'KES 25,000', unit: '', blurb: 'Routine spay surgery including anaesthesia and post-op monitoring.', popular: true },
      { name: 'Cat Spay', price: 'KES 20,000', unit: '', blurb: 'Routine spay surgery for cats, performed under general anaesthesia.' },
      { name: 'Dog Castration', price: 'KES 25,000', unit: '', blurb: 'Neutering surgery for male dogs, including anaesthesia and monitoring.' },
      { name: 'Cat Castration', price: 'KES 15,000', unit: '', blurb: 'Neutering surgery for male cats.' },
      { name: 'Fracture Repair & Orthopaedic Pinning', price: 'From KES 35,000', unit: '', blurb: 'Internal fixation for fractures, assessed and quoted after imaging.' },
      { name: 'Tumour Excision Surgery', price: 'From KES 25,000', unit: '', blurb: 'Surgical removal of masses and growths, with histopathology available on request.' },
    ],
    faqs: [
      { q: 'Is my pet monitored during anaesthesia?', a: 'Yes — every surgical patient is continuously monitored and recovers in-clinic before discharge.' },
      { q: 'What\'s the recovery time for a spay or castration?', a: 'Most pets are back to normal within 10–14 days, with a follow-up check and stitch removal included.' },
    ],
  },

  diagnostics: {
    title: 'Lab & Diagnostics',
    icon: 'lab',
    tagline: 'Fast, accurate answers — mostly same-day',
    description: 'In-house blood panels, imaging, and pathology so we can diagnose and start treatment quickly, without waiting days for outside labs.',
    items: [
      { name: 'Full Biochemistry Profile', price: 'KES 16,000', unit: '', blurb: 'Comprehensive blood chemistry panel covering organ function and metabolic health.', popular: true },
      { name: 'Digital X-Ray / Imaging', price: 'KES 4,500', unit: '', blurb: 'High-resolution digital imaging for bones, chest, and abdomen.' },
      { name: 'Abdominal Ultrasound', price: 'KES 6,500', unit: '', blurb: 'Non-sedated ultrasound scan for a closer look at internal organs.' },
      { name: 'Urinalysis', price: 'KES 4,500', unit: '', blurb: 'Urine testing to screen for infection, kidney issues, and metabolic disease.' },
      { name: 'Pre-Surgical Wellness Profile', price: 'KES 6,000', unit: '', blurb: 'Bloodwork to confirm your pet is fit for anaesthesia before any procedure.' },
      { name: 'CT Scan (Advanced Imaging)', price: 'From KES 50,000', unit: '', blurb: 'High-detail cross-sectional imaging for complex orthopaedic or internal cases.' },
    ],
    faqs: [
      { q: 'How fast do lab results come back?', a: 'Most in-house bloodwork and imaging results are ready the same day.' },
      { q: 'Do I need a referral for diagnostics?', a: 'No — diagnostics can be booked directly, or added on after a consultation if your vet recommends it.' },
    ],
  },

  dental: {
    title: 'Dental Care',
    icon: 'tooth',
    tagline: 'Healthy teeth, fresher breath, fewer hidden problems',
    description: 'Professional cleaning, extractions, and oral health assessments under anaesthesia — dental disease is one of the most common, and most overlooked, issues in pets.',
    items: [
      { name: 'Dental Scaling & Polishing', price: 'KES 6,500', unit: '/session', blurb: 'Ultrasonic scaling to remove tartar and plaque buildup, plus a polish.', popular: true },
      { name: 'Tooth Extraction', price: 'KES 4,500', unit: '', blurb: 'Removal of damaged or diseased teeth under anaesthesia.' },
      { name: 'Cat Dental Package', price: 'KES 1,600', unit: '/session', blurb: 'A dental package sized and priced for cats.' },
      { name: 'Oral Health Check-Up', price: 'Included', unit: 'with consultation', blurb: 'A quick oral exam bundled into your General Consultation (from KES 2,000).' },
      { name: 'Dental Aftercare & Pain Management', price: 'From KES 800', unit: '', blurb: 'Post-procedure pain relief and antibiotic care, priced per prescription.' },
      { name: 'Advanced Periodontal & Gum Disease Treatment', price: 'Quoted after exam', unit: '', blurb: 'Deeper treatment for advanced tartar, gum recession, or periodontal disease.' },
    ],
    faqs: [
      { q: 'How do I know if my pet needs dental care?', a: 'Bad breath, yellow-brown tartar, or reluctance to chew are the usual first signs — worth a check even without obvious symptoms.' },
      { q: 'Is anaesthesia necessary for dental cleaning?', a: 'Yes, for a thorough and safe clean below the gumline — your pet is monitored throughout.' },
    ],
  },

  emergency: {
    title: 'Emergency Care',
    icon: 'emergency',
    tagline: 'Here when it can\'t wait',
    description: 'Same-day urgent appointments for accidents, sudden illness, and critical conditions — including after-hours consultations and house calls.',
    items: [
      { name: 'After-Hours Emergency Consultation (10pm–8am)', price: 'KES 4,500', unit: '', blurb: 'Overnight emergency assessment and treatment plan.', popular: true },
      { name: 'Evening Emergency Consultation (5pm–10pm)', price: 'KES 3,500', unit: '', blurb: 'Urgent evening consultation outside normal clinic hours.' },
      { name: 'Ambulatory / House-Call Visit', price: 'KES 1,000', unit: '/visit', blurb: 'A vet dispatched to you for pets that can\'t safely be moved.' },
      { name: 'IV Fluid Therapy & Stabilisation', price: 'KES 1,500', unit: '', blurb: 'Rapid stabilisation for dehydration, shock, or acute illness.' },
      { name: 'Wound Cleaning & Dressing', price: 'From KES 2,500', unit: '', blurb: 'Emergency wound care, cleaning, and dressing to prevent infection.' },
      { name: 'Allergic Reaction & Sting Treatment', price: 'From KES 1,200', unit: '', blurb: 'Fast antihistamine treatment for bites, stings, and sudden allergic reactions.' },
    ],
    faqs: [
      { q: 'Do I need to call ahead for an emergency?', a: 'If possible, yes — a quick call lets our team prepare, but we\'ll always see a genuine emergency without one.' },
      { q: 'What counts as a pet emergency?', a: 'Trauma, difficulty breathing, repeated vomiting, collapse, seizures, or suspected poisoning — when in doubt, call us.' },
    ],
  },

  grooming: {
    title: 'Grooming & Hygiene',
    icon: 'scissors',
    tagline: 'Looking sharp is part of feeling good',
    description: 'Professional bathing, coat care, and hygiene services in our salon — sized and priced to your pet\'s breed and coat condition.',
    items: [
      { name: 'Grooming Wash (Bath & Blow-Dry)', price: 'KES 3,000', unit: '', blurb: 'A full bath, shampoo, and blow-dry service.', popular: true },
      { name: 'Full Grooming – Small Dogs & Cats (up to 15kg)', price: 'KES 3,500', unit: '/session', blurb: 'Complete groom including bath, trim, nails, and ears.' },
      { name: 'Full Grooming – Large & Giant Breeds', price: 'From KES 5,500', unit: '/session', blurb: 'Full groom scaled for larger dogs (25kg and up).' },
      { name: 'Matted Coat De-Shedding & Grooming', price: 'KES 4,000', unit: '', blurb: 'Specialist care for matted or heavily shedding coats.' },
      { name: 'Nail Clipping', price: 'KES 500', unit: '', blurb: 'Quick, stress-free nail trim — great as a standalone or add-on.' },
      { name: 'Cat Grooming (Short & Long Hair)', price: 'From KES 2,500', unit: '/session', blurb: 'Full grooming service sized for feline coats, short or long.' },
    ],
    faqs: [
      { q: 'How often should my pet be groomed?', a: 'Every 4–8 weeks depending on breed and coat type keeps things manageable between visits.' },
      { q: 'Can grooming be booked alongside a vet visit?', a: 'Yes — many owners combine a wellness check with a same-day groom.' },
    ],
  },

  boarding: {
    title: 'Boarding & Kennels',
    icon: 'home',
    tagline: 'Safe, comfortable stays, monitored around the clock',
    description: 'Daycare and overnight boarding with enrichment activities, monitored 24/7 by our kennel team — for dogs, cats, and small exotics.',
    items: [
      { name: 'Daycare (Half or Full Day)', price: 'From KES 650', unit: '', blurb: 'Supervised daytime stay with playtime and enrichment.', popular: true },
      { name: 'Small Dog & Cat Boarding', price: 'From KES 800', unit: '/day', blurb: 'Overnight boarding sized for smaller pets.' },
      { name: 'Medium Dog Boarding', price: 'KES 1,500', unit: '/day', blurb: 'Comfortable overnight kennel stay for medium-sized dogs.' },
      { name: 'Large Dog Boarding', price: 'KES 1,700', unit: '/day', blurb: 'Spacious kennel accommodation for large breeds.' },
      { name: 'Exotic & Small Pet Boarding', price: 'From KES 800', unit: '/day', blurb: 'Boarding for parrots and other small companion animals.' },
      { name: 'Kitten Boarding', price: 'KES 650', unit: '', blurb: 'A dedicated, quieter boarding option sized and priced for kittens.' },
    ],
    faqs: [
      { q: 'What should I bring when boarding my pet?', a: 'Their usual food, any medications, and a favourite toy or blanket help them settle in faster.' },
      { q: 'Is boarding available on short notice?', a: 'We\'ll always try — but booking a day or two ahead guarantees space, especially over holidays.' },
    ],
  },

  nutrition: {
    title: 'Nutrition Counselling',
    icon: 'leaf',
    tagline: 'The right diet is preventive medicine',
    description: 'Personalised diet plans for growth, weight management, and chronic disease support, built around a one-on-one consultation with your vet.',
    items: [
      { name: 'Weight Management Consultation', price: 'From KES 2,000', unit: '', blurb: 'A tailored plan to help pets reach and maintain a healthy weight.', popular: true },
      { name: 'Puppy & Kitten Growth Diet Planning', price: 'From KES 2,000', unit: '', blurb: 'Feeding guidance for healthy, well-paced growth in the first year.' },
      { name: 'Therapeutic Diet Transition', price: 'From KES 2,000', unit: '+ diet cost', blurb: 'Guided transition onto a prescription diet for conditions like kidney or joint disease.' },
      { name: 'Senior Pet Nutrition & Joint-Support Plan', price: 'From KES 2,000', unit: '', blurb: 'Diet adjustments to support ageing joints, mobility, and organ function.' },
      { name: 'Food Allergy & Sensitivity Assessment', price: 'From KES 2,000', unit: '', blurb: 'Investigating diet-related skin, coat, or digestive issues.' },
      { name: 'Prescription Diet Supply (e.g. Renal Food)', price: 'From KES 20,000', unit: '', blurb: 'Therapeutic diets stocked in-clinic for kidney, joint, and chronic-condition support.' },
    ],
    faqs: [
      { q: 'Do I need to bring my pet\'s current food?', a: 'It helps — bringing the label or packaging lets us give more specific recommendations.' },
      { q: 'Can nutrition counselling be combined with a wellness exam?', a: 'Yes, it\'s often done as part of the same visit, especially for weight or senior-care plans.' },
    ],
  },
}

export const serviceCatalogList = Object.entries(serviceCatalog).map(([slug, data]) => ({ slug, ...data }))
