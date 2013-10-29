# This file should contain all the record creation needed to seed the database with its default values.
# The data can then be loaded with the rake db:seed (or created alongside the db with db:setup).
#
# Examples:
#
#   cities = City.create([{ name: 'Chicago' }, { name: 'Copenhagen' }])
#   Mayor.create(name: 'Emanuel', city: cities.first)

["Sports", "Music"].each do |r|
  EventType.create(name: r)
end

["English", "Hindi", "Telugu"].each do |r|
  EventLanguage.create(name: r)
end



employee = Event.create!(name: 'naresh', start_at: "2013-10-28", end_at: "2013-10-30",city:'hyd',event_type_id: '1',event_language_id:'1,2')




