import {
  BarChart,
  Bar,
  XAxis,
  YAxis,
  Tooltip,
  ResponsiveContainer
} from "recharts";

export default function BranchChart({data}:any){

  return(

    <div className="bg-white p-6 rounded-xl shadow">

      <h2 className="font-bold mb-4">
        Branch Attendance
      </h2>

      <ResponsiveContainer width="100%" height={300}>

        <BarChart data={data}>

          <XAxis dataKey="branch"/>
          <YAxis/>
          <Tooltip/>

          <Bar dataKey="avg_attendance" fill="#2563eb"/>

        </BarChart>

      </ResponsiveContainer>

    </div>

  )

}